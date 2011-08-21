<?php

namespace BeSimple\RosettaBundle\Translation\Webservice;

/**
 * Google translate webservice implementation.
 * Will sadly be unavailable from december 2011.
 *
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class GoogleTranslator extends AbstractTranslator implements TranslatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function translate($text, array $locales, $currentLocale = null)
    {
        $url = 'https://ajax.googleapis.com/ajax/services/language/translate?v=1.0&q='.urlencode($text);

        foreach ($locales as $locale) {
            $url .= '&langpair='.($currentLocale ?: '').'%7C'.$locale;
        }

        if (isset($this->options['key']) && $this->options['key']) {
            $url .= '&key='.$this->options['key'];
        }

        if (isset($this->options['ip']) && $this->options['ip']) {
            $url .= '&userip='.(is_string($this->options['ip']) ? $this->options['ip'] : $_SERVER['SERVER_ADDR']);
        }

        $translations = array();
        $response = json_decode($this->sendRequest($url), true);

        foreach ($response['responseData'] as $index => $data) {
            $translations[$locales[$index]] = isset($data['responseData']) ? $data['responseData']['translatedText'] : null;
        }

        return $translations;
    }
}
