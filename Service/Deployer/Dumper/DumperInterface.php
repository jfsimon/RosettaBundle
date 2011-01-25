<?php

namespace Bundle\RosettaBundle\Service\Deployer\Dumper;

interface DumperInterface
{
    public function __construct($filename=null);
    public function setFile($bundle, $domain, $locale);
    public function dump($message, $translation);
}