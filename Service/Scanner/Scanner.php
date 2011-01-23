<?php

namespace Bundle\RosettaBundle\Service\Scanner;

use Bundle\RosettaBundle\Service\Model\Model;
use Bundle\RosettaBundle\Service\Locator\Locator;
use Bundle\RosettaBundle\Service\Translator\Translator;
use Bundle\RosettaBundle\Service\Workflow\Input;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Finder\Finder;

class Scanner
{
    protected $locator;
    protected $scanners;

    public function __construct(Locator $locator, array $config)
    {
        $this->locator = $locator;
        $this->scanners = $config['scanners'];
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

    public function scanFile($file, $bundle=null, $store=false)
    {
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

        return $this->getWorkflowInputs($messages, $bundle);
    }

    public function scanBundle($bundle)
    {
        $path = $this->locator->locateBundle($bundle);
        $inputs = array();

        foreach($this->locator->findFiles($path, array_keys($this->scanners)) as $file) {
            $inputs = array_merge($inputs, $this->scanFile($file, $bundle));
        }

        return $inputs;
    }

    public function scanProject(array $config=array())
    {
        $inputs = array();

        foreach($this->locator->getBundles() as $bundle) {
            $inputs = array_merge($inputs, $this->scanBundle($bundle));
        }

        return $inputs;
    }

    protected function getWorkflowInputs(array $messages, $bundle)
    {
        $inputs = array();

        foreach($messages as $message) {
            $inputs[] = new Input(
                $message['text'],
                $message['parameters'],
                $message['domain'],
                $bundle,
                $message['choice'],
                false
            );
        }

        return $inputs;
    }
}