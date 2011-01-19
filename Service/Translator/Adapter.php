<?php

namespace Bundle\RosettaBundle\Service\Translator;

class Adapter
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }
}