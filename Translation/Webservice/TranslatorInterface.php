<?php

namespace BeSimple\RosettaBundle\Translation\Webservice;

/**
 * Interface for translation webservices.
 *
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
interface TranslatorInterface
{
    /**
     * Translates a text.
     * Returns an array indexed with translation locales.
     *
     * @param array       $texts      A text to translate
     * @param array       $toLocales  An array of translation locales
     * @param string|null $fromLocale The source text locale
     *
     * @return array An array of translations
     */
    function translate($text, array $toLocales, $fromLocale = null);

    /**
     * Translates a batch of texts.
     * Returns an array of arrays indexed with translation locales.
     *
     * @param array       $texts      An array of texts to translate
     * @param array       $toLocales  An array of translation locales
     * @param string|null $fromLocale The source texts locale
     *
     * @return array An array of translations
     */
    function translateBatch(array $text, array $toLocales, $fromLocale = null);
}
