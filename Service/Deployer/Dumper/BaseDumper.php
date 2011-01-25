<?php

namespace Bundle\RosettaBundle\Service\Deployer\Dumper;

use Bundle\RosettaBundle\Service\Locator\Locator;

abstract class BaseDumper
{
    abstract protected $extension;
    protected $filename;

    public function __construct(Locator $locator, $filename=null)
    {
        $this->locator = $locator;
        $this->filename = is_string($filename) ?: $filename;
    }

    public function setFile($bundle, $domain, $locale)
    {
        $this->filename = $this->locator->locateBundle()
            .'/Resources/translation/'
            .$domain.'_'.$locale.'.'.$this->extension;
    }

    public function dump($message, $translation)
    {
        file_put_contents($this->filename, $this->render());
    }
}