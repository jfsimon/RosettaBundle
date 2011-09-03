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
     *
     * @param array  $texts      A text to translate
     * @param string $fromLocale The source text locale
     * @param array  $toLocales  An array of translation locales
     *
     * @return Response A Translations instance
     */
    function translate($text, $fromLocale, array $toLocales);

    /**
     * Translates a batch of texts.
     *
     * @param array  $texts      An array of texts to translate
     * @param string $fromLocale The source texts locale
     * @param array  $toLocales  An array of translation locales
     *
     * @return array An array of Translations instances
     */
    function translateBatch(array $text, $fromLocale, array $toLocales);
}
