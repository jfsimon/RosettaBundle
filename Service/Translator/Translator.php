<?php

namespace Bundle\RosettaBundle\Service\Translator;

class Translator
{
    protected $adapter;
    protected $config;

    public function __construct(array $config)
    {
        $config = $config['translator'];
        $adapter = $config['adapter'];
        unset($config['adapter']);

        $this->adapter = new $adapter($config);
    }

    public function translate($string, $locale, $fromLocale=null)
    {
        return $this->adapter->translate($string, $locale, $fromLocale);
    }
}