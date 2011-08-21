<?php

namespace BeSimple\RosettaBundle\Translation\Webservice;

/**
 * Base class for translator webservices.
 *
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
abstract class AbstractTranslator
{
    /**
     * @var array
     */
    protected $options;

    /**
     * Constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $this->options = $options;
    }

    /**
     * Returns the options.
     *
     * @return array An array of options
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Sets the options.
     *
     * @param array $options An array of options
     *
     * @return TranslatorInterface A TranslatorInterface instance
     */
    public function setOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Translates a batch of texts.
     *
     * @param array       $texts      An array of texts to translate
     * @param array       $toLocales  An array of translation locales
     * @param string|null $fromLocale The source texts locale
     *
     * @return array An array of translations
     */
    public function translateBatch(array $texts, array $toLocales, $fromLocale = null)
    {
        $translations = array();

        foreach ($texts as $key => $text) {
            foreach ($this->translate($text, $toLocales, $fromLocale) as $locale => $translation) {
                if (!isset($translations[$locale])) {
                    $translations[$locale] = array();
                }

                $translations[$locale][$key] = $translation;
            }
        }

        return $translations;
    }

    /**
     * Reads result of a GET request to given URL.
     *
     * @param string $url An URL
     *
     * @return string The response content
     */
    protected function sendRequest($url)
    {
        return file_get_contents($url);
    }
}
