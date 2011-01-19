<?php

namespace Bundle\RosettaBundle\Service\Live;

use Symfony\Bundle\FrameworkBundle\Translation\Translator as BaserTranslator;

class Translator extends BaseTranslator
{
    public function trans($id, array $parameters = array(), $domain = 'messages', $locale = null)
    {
        if (!isset($locale)) {
            $locale = $this->getLocale();
        }

        $this->container->get('rosetta.live')->handle($id, $parameters, $domain, $locale, false, debug_backtrace());

        return parent::trans($id, $parameters, $domain, $locale);
    }

    public function transChoice($id, $number, array $parameters = array(), $domain = 'messages', $locale = null)
    {
        if (!isset($locale)) {
            $locale = $this->getLocale();
        }

        $this->container->get('rosetta.live')->handle($id, $parameters, $domain, $locale, true, debug_backtrace());

        return parent::transChoice($id, $number, $parameters, $domain, $locale);
    }
}