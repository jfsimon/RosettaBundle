<?php

namespace Bundle\RosettaBundle\Service\Translator;

interface AdapterInterface
{
    public function __construct(array $config);
    public function translate($string, $locale, $fromLocale=null);
}