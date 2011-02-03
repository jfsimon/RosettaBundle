<?php

namespace Bundle\RosettaBundle\Service;

use Bundle\RosettaBundle\Service\Importer;

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

    public function scanFile($filename, $bundle=null)
    {
        return $this->scanner->scanFile($filename, $bundle, $this->options);
    }

    public function scanBundle($bundle)
    {
        return $this->scanner->scanBundle($bundle, $this->options);
    }

    public function scanProject()
    {
        return $this->scanner->scanProject($this->options);
    }

    public function importFile($filename, $bundle=null)
    {
        return $this->importer->importFile($filename, $bundle);
    }

    public function importBundle($bundle)
    {
        return $his->importer->importBundle($bundle);
    }

    public function importProject()
    {
        return $his->importer->importProject();
    }

    public function deployDomain($bundle, $domain)
    {
        return $this->deployer->deployDomainName($bundle, $domain);
    }

    public function deployBundle($bundle)
    {
        return $this->deployer->deployBundle($bundle);
    }

    public function deployProject()
    {
        return $this->deployer->deployProject();
    }

}