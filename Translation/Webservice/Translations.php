<?php

namespace BeSimple\RosettaBundle\Translation\Webservice;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class Translations
{
    /**
     * @var array
     */
    private $translations;

    /**
     * @var array
     */
    private $errors;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->translations = array();
        $this->errors       = array();
    }

    /**
     * @param string $locale
     * @param string $translation
     *
     * @return Translations
     */
    public function set($locale, $translation)
    {
        $this->translations[$locale] = $translation;

        return $this;
    }

    /**
     * @param string $locale
     *
     * @return string|null
     */
    public function get($locale)
    {
        return isset($this->translations[$locale]) ? $this->translations[$locale] : null;
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->translations;
    }

    /**
     * @param string $locale
     * @param string $error
     *
     * @return Translations
     */
    public function setError($locale, $error)
    {
        $this->errors[$locale] = $error;

        return $this;
    }

    /**
     * @param string $locale
     *
     * @return string|null
     */
    public function getError($locale)
    {
        return isset($this->errors[$locale]) ? $this->errors[$locale] : null;
    }

    /**
     * @return array
     */
    public function allErrors()
    {
        return $this->errors;
    }

    public function allLocales()
    {
        return array_merge(array_keys($this->translations), array_keys($this->errors));
    }
}
