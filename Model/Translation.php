<?php

namespace BeSimple\RosettaBundle\Model;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class Translation extends AbstractEntity
{
    /**
     * @var Message
     */
    protected $message;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var string
     */
    protected $text;

    /**
     * @var string(36)
     */
    protected $hash;

    /**
     * @var int
     */
    protected $rating;

    /**
     * @var int
     */
    protected $isSelected;

    /**
     * @param string|null $locale
     * @param string|null $text
     */
    public function __construct($locale = null, $text = null)
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->locale    = $locale;
        $this->text      = $text;
        $this->rating    = 0;
    }

    /**
     * @param Translation $translation
     * @return bool
     */
    public function isSameAs(Translation $translation)
    {
        return $translation->getMessage()->isSameAs($this->message)
            && $translation->getText() === $this->text;
    }

    /**
     * Update computed values by using a helper.
     *
     * @param HelperInterface $helper A helper instance
     *
     * @return Message
     */
    public function update(HelperInterface $helper)
    {
        $this->hash  = $helper->hash($this->text);

        return $this;
    }

    /**
     * @param Message $message
     * @return Translation
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $locale
     * @return Translation
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param string $text
     * @return Translation
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
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param int $rating
     * @param string $text
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * @return int
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param int $isSelected
     */
    public function setIsSelected($isSelected)
    {
        $this->isSelected = $isSelected;
    }

    /**
     * @return int
     */
    public function getIsSelected()
    {
        return $this->isSelected;
    }
}
