<?php

namespace Bundle\RosettaBundle\Scanner;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Finder\Finder;

class Service
{
    protected $em;
    protected $locator;
    protected $translator;
    protected $scanners;
    protected $options;

    public function __construct(EntityManager $em, Locator $locator, Translator $translator, array $scanners, array $options)
    {
        $this->em = $em;
        $this->locator = $locator;
        $this->translator = $translator;
        $this->scanners = $scanners;
        $this->options = $options;
    }

    public function getOption($name)
    {
        return $this->options[$name];
    }

    public function setOption($name, $value)
    {
        $this->options[$name] = $value;
    }

    public function setCanner($extension, $class)
    {
        $this->scanners[$extension] = $class;
    }

    public function getScanner($extension)
    {
        $class = $this->scanners[$extension];
        return new $class();
    }

    public function scanFile($file, $bundle=null, array $options=array())
    {
        $options = array_merge($this->options, $options);

        if(is_null($bundle)) {
            $bundle = $this->locator->guessBundle($file);
        }

        $extension = substr($file, strrpos($file, '.') + 1);

        if(! isset($this->scanners[$extension])) {
            return false;
        }

        $scanner = $this->getScanner();
        $scanner->loadFile($file);

        $messages = $scanner->getMessages();

        if($options['store']) {
            $this->storeMessages($messages, $bundle, $options);
        }

        return $messages;
    }

    public function scanBundle($bundle, array $options=array())
    {
        $options = array_merge($this->options, $options);
        $store = $options['store'];
        $options['store'] = false;

        $path = $this->locator->locateBundle($bundle);
        $messages = array();

        foreach($this->findFiles($path) as $file) {
            $messages = array_merge($messages, $this->scanFile($file, $bundle, $options));
        }

        if($options['store'] = $store) {
            $this->storeMessages($messages, $options);
        }

        return $messages;
    }

    public function scanProject(array $options=array())
    {
        $options = array_merge($this->options, $options);
        $store = $options['store'];
        $options['store'] = false;

        $messages = array();

        foreach($this->locator->getBundles() as $bundle) {
            $messages = array_merge($messages, $this->scanBundle($bundle, $options));
        }

        if($options['store'] = $store) {
            $this->storeMessages($messages, $options);
        }

        return $messages;
    }

    protected function findFiles($path)
    {
        $finder = new Finder();
        $finder->files();

        foreach(array_keys($this->scanners) as $extension) {
            $finder->name('*.'.$extension);
        }

        return $finder->in($path);
    }

    protected function storeMessages(array $messages, $bundle, array $options=array())
    {
        $options = array_merge($this->options, $options);

        $mr = $this->em->getRepository('Bundle\\RosettaBundle\\Model\\Entity\\Message');
        $dr = $this->em->getRepository('Bundle\\RosettaBundle\\Model\\Entity\\Domain');

        foreach($messages as $message) {
            $entity = $mr->getOrCreate($message->getText());

            $entity->setDomain($dr->getOrCreate($bundle, $message->getDomain()));
            $entity->setIsChoice($message->isChoice());
            $entity->setParameters($message->getParameters());
            $entity->setIsLive($options['live']);

            if($options['translate']) {
                $entity = $this->translator->translateEntity($entity);
            }

            $this->em->persist($entity);
        }

        if(count($messages)) {
            $this->em->flush();
        }
    }
}