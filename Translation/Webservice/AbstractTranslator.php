<?php

namespace BeSimple\RosettaBundle\Translation\Webservice;

/**
 * Base class for translator webservices.
 *
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
abstract class AbstractTranslator implements TranslatorInterface
{
    /**
     * @var array
     */
    private $options;

    /**
     * @var string
     */
    protected $error;

    /**
     * Constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $this->options = $options;
        $this->error   = null;
    }

    /**
     * {@inheritdoc}
     */
    public function translateBatch(array $texts, $fromLocale, array $toLocales)
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
     * {@inheritdoc}
     */
    public function translate($text, $fromLocale, array $toLocales)
    {
        $translations = new Translations();

        foreach ($toLocales as $toLocale) {
            $request  = $this->buildRequest($text, $fromLocale, $toLocale);
            $response = $request->getResponse(Request::DECODE_JSON);

            if (is_array($response)) {
                $response = $this->parseResponse($response);

                if (is_null($response)) {
                    $translations->setError($toLocale, $this->error);
                } else {
                    $translations->set($toLocale, $response);
                }
            } else {
                $translations->setError($toLocale, $request->getLastError());
            }
        }

        return $translations;
    }

    /**
     * Returns an option value.
     *
     * @param string $key     Option key
     * @param mixed  $default Default value
     *
     * @return mixed
     */
    public function getOption($key, $default = null)
    {
        return isset($this->options[$key]) ? $this->options[$key] : $default;
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @param string $text
     * @param string $fromLocale
     * @param string $toLocale
     * @return Request
     */
    abstract protected function buildRequest($text, $fromLocale, $toLocale);

    /**
     * @param array $response
     *
     * @return string|null
     */
    abstract protected function parseResponse(array $response);
}
