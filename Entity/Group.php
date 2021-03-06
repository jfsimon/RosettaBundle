<?php

namespace BeSimple\RosettaBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class Group extends AbstractEntity
{
    const UNKNOWN_BUNDLE = 'Unknown';
    const UNKNOWN_DOMAIN = '_unknown';

    /**
     * @var string
     */
    protected $bundle;

    /**
     * @var string
     */
    protected $domain;

    /**
     * @var ArrayCollection
     */
    protected $messages;

    /**
     * @param string|null $bundle
     * @param string|null $domain
     */
    public function __construct($bundle = self::UNKNOWN_BUNDLE, $domain = self::UNKNOWN_DOMAIN)
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->bundle    = $bundle;
        $this->domain    = $domain;
        $this->messages  = new ArrayCollection();
    }

    /**
     * @param Group $group
     * @return bool
     */
    public function isSameAs(Group $group)
    {
        return $group->getBundle() === $this->bundle
            && $group->getDomain() === $this->domain;
    }

    /**
     * @param string $bundle
     * @return Group
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
     * @return Group
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
     * @return ArrayCollection
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @param Message $translation A Message instance
     *
     * @return Group This instance
     */
    public function addMessage(Message $message)
    {
        $message->setGroup($this);
        $this->messages->add($message);

        return $this;
    }
}
