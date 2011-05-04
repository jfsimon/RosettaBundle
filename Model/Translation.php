<?php

namespace BeSimple\RosettaBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;

abstract class Translation extends Model
{
    /**
     * Related message.
     *
     * @var Message
     */
    protected $message;

    /**
     * Locale.
     *
     * @var string
     */
    protected $locale;

    /**
     * Translated text.
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
     * Rating.
     *
     * @var int
     */
    protected $rating;

    /**
     * Is translation selected for this message / locale.
     *
     * @var boolean
     */
    protected $isSelected;

    public function __construct($message = null, $locale = null, $text = null)
    {
        $this->createdAt = new \DateTime();

        if ($message instanceof Message) {
            $this->setMessage($message);
        }

        if ($locale) {
            $this->setLocale($locale);
        }

        if ($text) {
            $this->setText($text);
        }
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage(Message $message)
    {
        $this->message = $message;
    }

    public function getDomain()
    {
        if ($this->message instanceof Message) {
            return $this->message->getDomain();
        }

        return null;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function setLocale($locale)
    {
        $this->locale = $locale;
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

    public function getRating()
    {
        return $this->rating;
    }

    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    public function getIsSelected()
    {
        return $this->isSelected;
    }

    public function setIsSelected($isSelected)
    {
        $this->isSelected = $isSelected;
    }

    public function isLike(Translation $translation)
    {
        return $this->message->isLike($translation->getMessage()) && $this->hash === $translation->getHash();
    }

}