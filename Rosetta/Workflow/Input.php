<?php

namespace BeSimple\RosettaBundle\Rosetta\Workflow;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class Input
{
    const HASH_SEPARATOR = '|';

    /**
     * @var string
     */
    private $bundle;

    /**
     * @var string
     */
    private $domain;

    /**
     * @var string
     */
    private $text;

    /**
     * @var bool
     */
    private $isChoice;

    /**
     * @var string[]
     */
    private $parameters;

    /**
     * @var array
     */
    private $translations;

    /**
     * Constructor.
     *
     * @param string|null     $bundle A bundle name
     * @param string|null     $domain A domain name
     * @param string|null     $text   A message
     */
    public function __construct($bundle = null, $domain = 'messages', $text = null)
    {
        $this->bundle       = $bundle;
        $this->domain       = $domain;
        $this->text         = $text;
        $this->parameters   = array();
        $this->translations = array();
    }

    /**
     * Returns a unique identifier.
     *
     * @return string Identifier
     */
    public function getIdentifier()
    {
        return implode(self::HASH_SEPARATOR, array($this->bundle, $this->domain, sha1($this->text)));
    }

    /**
     * Tests input validity.
     *
     * @return bool
     */
    public function isValid()
    {
        return strlen($this->bundle) && strlen($this->domain) && strlen($this->text);
    }

    /**
     * @param string $bundle
     *
     * @return Input
     */
    public function setBundle($bundle)
    {
        $this->bundle = $bundle;

        return $this;
    }

    /**
     * @return string
     */
    public function getBundle()
    {
        return $this->bundle;
    }

    /**
     * @param string $domain
     *
     * @return Input
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param string $text
     *
     * @return Input
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param boolean $isChoice
     *
     * @return Input
     */
    public function setIsChoice($isChoice)
    {
        $this->isChoice = $isChoice;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsChoice()
    {
        return $this->isChoice;
    }

    /**
     * @param array $parameters
     *
     * @return Input
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param string[] $parameters
     *
     * @return Input
     */
    public function mergeParameters(array $parameters)
    {
        $this->parameters = array_merge($this->parameters, $parameters);

        return $this;
    }

    /**
     * @param array $translations
     *
     * @return Input
     */
    public function setTranslations(array $translations)
    {
        $this->translations = $translations;

        return $this;
    }

    /**
     * @return array
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * @param string $locale
     * @param string $text
     *
     * @return Input
     */
    public function addTranslation($locale, $text)
    {
        if (!isset($this->translations[$locale])) {
            $this->translations[$locale] = array();
        }

        $this->translations[$locale][] = $text;

        return $this;
    }

    /**
     * @param array $translations
     *
     * @return Input
     */
    public function mergeTranslations(array $translations)
    {
        foreach ($translations as $locale => $texts) {
            foreach ($texts as $text) {
                $this->addTranslation($locale, $text);
            }
        }

        return $this;
    }
}
