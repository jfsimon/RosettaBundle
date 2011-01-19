<?php

namespace Bundle\RosettaBundle\Service\Scanner;

use Bundle\RosettaBundle\Service\Model\Model;
use Bundle\RosettaBundle\Service\Locator\Locator;
use Bundle\RosettaBundle\Service\Translator\Translator;
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

        return $scanner->getMessages();;
    }

    public function scanBundle($bundle)
    {
        $path = $this->locator->locateBundle($bundle);
        $messages = array();

        foreach($this->locator->findFiles($path, array_keys($this->scanners)) as $file) {
            $messages = array_merge($messages, $this->scanFile($file, $bundle));
        }

        return $messages;
    }

    public function scanProject(array $config=array())
    {
        $messages = array();

        foreach($this->locator->getBundles() as $bundle) {
            $messages = array_merge($messages, $this->scanBundle($bundle));
        }

        return $messages;
    }
}