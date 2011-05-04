<?php

namespace BeSimple\RosettaBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;

abstract class Message extends Model
{
    /**
     * Related domain.
     *
     * @var Domain
     */
    protected $domain;

    /**
     * Text to translate.
     *
     * @var string
     */
    protected $text;

    /**
     * Text hash.
     *
     * @var string
     */
    protected $hash;

    /**
     * Is a choice message.
     *
     * @var boolean
     */
    protected $isChoice;

    /**
     * Message parameters.
     *
     * @var array
     */
    protected $parameters;

    /**
     * Related translations.
     *
     * @var ArrayCollection
     */
    protected $translations;

    public function __construct($domain = null, $text = null)
    {
        $this->createdAt = new \DateTime();

        if ($domain instanceof Domain) {
            $this->setDomain($domain);
        }

        if ($text) {
            $this->setText($text);
        }

        $this->messages = array();
        $this->translations = new ArrayCollection();
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function setDomain(Domain $domain)
    {
        $this->domain = $domain;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setText($text)
    {
        $this->text = $text;
        $this->hash = self::hash($text);
    }

    public function getHash()
    {
        return $this->hash;
    }

    public function getIsChoice()
    {
        return $this->isChoice();
    }

    public function setIsChoice($isChoice)
    {
        $this->isChoice = $isChoice;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
    }

    public function getTranslations()
    {
        return $this->translations->toArray();
    }

    public function setTranslations(array $translations)
    {
        $this->messages->clear();

        foreach ($translations as $translation) {
            $this->mergeTranslation($translation);
        }
    }

    public function getLocaleTranslations($locale)
    {
        $translations = array();

        foreach ($this->translations as $translation) {
            if ($translation->getLocale() === $locale) {
                $traslations[] = $translation;
            }
        }

        return $translations;
    }

    public function getSelectedTranslations()
    {
        $translations = array();

        foreach ($this->translations as $translation) {
            if ($translation->getIsSelected()) {
                $traslations[] = $translation;
            }
        }

        return $translations;
    }

    public function getSelectedTranslation($locale)
    {
        $translations = array();

        foreach ($this->translations as $translation) {
            if ($translation->getLocale() === $locale && $translation->getIsSelected()) {
                $traslations[] = $translation;
            }
        }

        return $translations;
    }

    public function setSelectedTranslation(Translation $translation)
    {
        foreach (array_keys($this->getLocaleTranslations($translation->getLocale())) as $index) {
            $this->translations[$index]->setIsSDelected(false);
        }

        $index = $this->translations->indexOf($translation);

        if ($index !== false) {
            $this->translations[$index]->setIsSDelected(true);
        }
    }

    public function countTranslations()
    {
        return $this->translations->count();
    }

    public function walkTranslations($callback)
    {
        return array_walk($this->translations->toArray(), $callback);
    }

    public function mergeTranslations(array $translations)
    {
        foreach ($translations as $translation) {
            $this->mergeTranslation($translation);
        }
    }

    public function mergeTranslation(Translation $translation)
    {
        foreach ($this->translations as $index => $innerTranslation) {
            if ($innerTranslation->isLike($translation)) {
                $rating = max(array($translation->getRating(), $innerTranslation->getRating()));
                $this->translations[$index]->setRating($rating);
                return;
            }
        }

        $this->translations->add($translation);
    }

    public function isLike(Message $message)
    {
        return $this->domain->isLike($message->getDomain()) && $this->hash === $message->getHash();
    }

}
