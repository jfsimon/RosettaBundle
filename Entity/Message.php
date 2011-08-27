<?php

namespace BeSimple\RosettaBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class Message extends AbstractEntity
{
    /**
     * @var Group
     */
    protected $group;

    /**
     * @var string
     */
    protected $text;

    /**
     * @var string
     */
    protected $hash;

    /**
     * @var bool
     */
    protected $isChoice;

    /**
     * @var bool
     */
    protected $isKey;

    /**
     * @var string[]
     */
    protected $parameters;

    /**
     * @var ArrayCollection
     */
    protected $translations;

    /**
     * Constructor.
     *
     * @param string|null $text
     *
     * @param array $parameters
     */
    public function __construct(Group $group = null, $text = null, array $parameters = array())
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->group     = $group;

        if (!is_null($text)) {
            $this->setText($text);
        }

        $this->parameters   = $parameters;
        $this->translations = new ArrayCollection();
    }

    /**
     * @param Message $message
     *
     * @return bool
     */
    public function isSameAs(Message $message)
    {
        return $message->getGroup()->isSameAs($this->group)
            && $message->getText() === $this->text;
    }

    /**
     * Update computed values by using a helper.
     *
     * @param HelperInterface $helper A helper instance
     *
     * @return Message
     */
    public function cleanup(HelperInterface $helper)
    {
        $this->hash  = $helper->hash($this->text);
        $this->isKey = $helper->isKey($this->text);

        if (is_null($this->isChoice)) {
            $this->isChoice = $helper->isChoice($this->text);
        }

        foreach ($this->translations as $translation) {
            $translation->cleanup($helper);
        }

        return $this;
    }

    /**
     * @param Group $group
     *
     * @return Message
     */
    public function setGroup($group)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * @return Group
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param string $text
     *
     * @return Message
     */
    public function setText($text)
    {
        $this->text  = $text;

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
     * @return boolean
     */
    public function getIsChoice()
    {
        return $this->isChoice;
    }

    /**
     * @param bool $isChoice
     *
     * @return Message
     */
    public function setIsChoice($isChoice)
    {
        $this->isChoice = $isChoice;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsKey()
    {
        return $this->isKey;
    }

    /**
     * @param string[] $parameters
     *
     * @return Message
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
     * @return ArrayCollection
     */
    public function getTranslations()
    {
        return $this->translations;
    }
}
