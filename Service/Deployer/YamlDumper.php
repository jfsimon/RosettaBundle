<?php

namespace Bundle\RosettaBundle\Service\Deployer;

use Symfony\Component\Yaml\Yaml;

class YamlDumper extends Dumper implements DumperInterface
{
    public function render(array $translations)
    {
        return Yaml::dump($translations);
    }

    protected function getExtension()
    {
        return 'yml';
    }
}