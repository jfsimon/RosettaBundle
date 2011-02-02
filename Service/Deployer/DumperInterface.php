<?php

namespace Bundle\RosettaBundle\Service\Deployer;

interface DumperInterface
{
    public function dump(array $translations, $filename);
    public function render(array $translations);
}