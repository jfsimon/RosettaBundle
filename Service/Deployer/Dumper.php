<?php

namespace Bundle\RosettaBundle\Service\Deployer;

use Bundle\RosettaBundle\Service\Locator\Locator;

abstract class Dumper
{
    public function dump(array $translations, $filename)
    {
        $filename = $filename.'.'.$this->getExtension();
        file_put_contents($filename, $this->render($translations));
    }

    abstract public function render(array $translations);
    abstract protected function getExtension();
}