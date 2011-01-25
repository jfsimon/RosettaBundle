<?php

namespace Bundle\RosettaBundle\Service\Translator;

class Translator
{
    protected $locale;
    protected $adapter;
    protected $config;

    public function __construct(array $config)
    {
        $this->locale = $config['locale'];
        $adapter = $config['adapter'];
        unset($config['locale'], $config['adapter']);
        $this->adapter = new $adapter($config);
    }

    public function translate($string, $locales, $fromLocale=null)
    {
        if(! is_array($locales)) {
            $locales = array($locales);
        }

        foreach($locales as $locale) {
            $translations[$locale] = $this->adapter->translate($string, $fromLocale ?: $this->locale, $locale);
        }

        if(count($locales) === 1) {
            return $translations[$locales[0]];
        }

        return $translations;
    }
}