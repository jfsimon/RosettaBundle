<?php

namespace BeSimple\RosettaBundle\Rosetta\Collector;

use BeSimple\RosettaBundle\Rosetta\Workflow\Input;
use BeSimple\RosettaBundle\Rosetta\Factory;
use BeSimple\RosettaBundle\Rosetta\Locator;
use BeSimple\RosettaBundle\Translation\Webservice\TranslatorInterface;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class Collector extends AbstractCollector
{
    /**
     * @var string
     */
    private $sourceLocale;

    /**
     * @var array
     */
    private $translationLocales;

    /**
     * Constructor.
     *
     * @param Factory $factory A Factory instance
     * @param Locator $locator A Locator instance
     */
    public function __construct(Factory $factory, Locator $locator, $sourceLocale, array $translationLocales)
    {
        parent::__construct($factory, $locator);

        $this->sourceLocale       = $sourceLocale;
        $this->translationLocales = $translationLocales;
    }

    /**
     * Collects a message.
     *
     * @param string      $file   A filename from witch determine the bundle name
     * @param string|null $domain A domain name
     * @param string|null $locale A locale
     *
     * @return Input An Input instance
     */
    public function collect($file, $text, $domain, array $parameters = array())
    {
        $bundle = $this->locator->guessBundleName($file);
        $input  = new Input($bundle, $domain, $text, $parameters);

        $this->add($input);

        return $input;
    }

    /**
     * Translates a message.
     *
     * @param string      $text   A text to translate
     * @param string|null $locale A locale or null
     *
     * @return array An array of translations
     */
    public function translate($text)
    {
        return $this
            ->factory
            ->getTranslator()
            ->translate($text, $this->translationLocales, $this->sourceLocale)
        ;
    }

    /**
     * Collects and translates a message.
     *
     * @param string $file       A filename from witch determine the bundle name
     * @param string $domain     A domain name
     * @param string $text       A text to translate
     * @param array  $parameters An array of parameters
     *
     * @return array An array of translations
     */
    public function collectAndTranslate($file, $domain, $text, array $parameters = array())
    {
        $input        = $this->collect($file, $text, $domain, $parameters);
        $translations = $this->translate($text);

        foreach ($translations as $locale => $translation) {
            $input->addTranslation($locale, $translation);
        }

        return $translations;
    }
}
