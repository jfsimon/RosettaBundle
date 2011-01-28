<?php

namespace Bundle\RosettaBundle\Service\Workflow;

class Input
{
    protected $bundle;
    protected $domain;
    protected $text;
    protected $isChoice;
    protected $parameters;
    protected $isLive;
    protected $translations;

    public function __construct($text, array $parameters=array(), $domain='default', $bundle=null, $isChoice=false, $isLive=false, array $translations=array())
    {
        $this->setText($text);
        $this->setParameters($parameters);
        $this->setDomain($domain);
        $this->setBundle($bundle);
        $this->setIsChoice($isChoice);
        $this->setIsLive($isLive);
        $this->setTranslations($translations);
    }

    public function getBundle()
    {
        return $this->bundle;
    }

	public function getDomain()
    {
        return $this->domain;
    }

	public function getText()
    {
        return $this->text;
    }

	public function getIsChoice()
    {
        return $this->isChoice;
    }

	public function getIsLive()
    {
        return $this->isLive;
    }

    public function getTranslations()
    {
        return $this->translations;
    }

	public function getParameters()
    {
        return $this->parameters;
    }

    public function hasTranslations()
    {
        return count($this->translations);
    }

    public function hasParameters()
    {
        return count($this->parameters);
    }

	public function setBundle($bundle)
    {
        $this->bundle = $bundle ? trim($bundle) : null;
    }

	public function setDomain($domain)
    {
        $this->domain = trim($domain);
    }

	public function setText($text)
    {
        $this->text = trim($text);
    }

	public function setIsChoice($isChoice)
    {
        $this->isChoice = (bool)$isChoice;
    }

    public function setTranslations(array $translations)
    {
        $this->translations = $translations;
    }

    public function addTranslation($locale, $translation)
    {
        $this->translations[trim($locale)] = trim($translation);
    }

	public function setParameters(array $parameters)
    {
        foreach($parameters as $parameter) {
            $this->addParameter($parameter);
        }
    }

    public function addParameter($parameter)
    {
        $this->parameters[] = trim($parameter);
    }

	public function setIsLive($isLive)
    {
        $this->isLive = (bool)$isLive;
    }



}