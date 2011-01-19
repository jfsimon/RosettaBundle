<?php

namespace Bundle\Service\RosettaBundle;

use Bundle\RosettaBundle\Service\Scanner\Scanner;
use Bundle\RosettaBundle\Service\Importer\Importer;
use Bundle\RosettaBundle\Service\Translator\Translator;
use Bundle\RosettaBundle\Service\Deployer\Deployer;

class Rosetta
{
    protected $scanner;
    protected $importer;
    protected $translator;
    protected $deployer;
    protected $options;

    public function __construct(Scanner $scanner, Importer $importer, Translator $translator, Deployer $deployer, $options=array())
    {
        $this->scanner = $scanner;
        $this->importer = $importer;
        $this->translator = $translator;
        $this->deployer = $deployer;
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

    public function translate($text, $locale)
    {
        return $this->translator->translate($text, $locale);
    }

    public function scanFile($file, $bundle=null)
    {
        return $this->scanner->scanFile($file, $bundle, $this->options);
    }

    public function scanBundle($bundle)
    {
        return $this->scanner->scanBundle($bundle, $this->options);
    }

    public function scanProject()
    {
        return $this->scanner->scanProject($this->options);
    }

    public function importFile($file, $bundle=null)
    {
    }

    public function importBundle($bundle)
    {
    }

    public function importProject()
    {
    }

    public function deployDomain($bundle, $domain)
    {
    }

    public function deployBundle($bundle)
    {
    }

    public function deployProject()
    {
    }

}