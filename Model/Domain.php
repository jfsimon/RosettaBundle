<?php

namespace BeSimple\RosettaBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;

abstract class Domain extends Model
{
    /**
     * Bundle name.
     *
     * @var string
     */
    protected $bundle;

    /**
     * Domain name.
     *
     * @var string
     */
    protected $domain;

    /**
     * Related messages.
     *
     * @var ArrayCollection
     */
    protected $messages;

    public function __construct($bundle = null, $domain = null)
    {
        $this->createdAt = new \DateTime();

        if ($bundle) {
            $this->setBundle($bundle);
        }

        if ($domain) {
            $this->setDomain($domain);
        }

        $this->messages = new ArrayCollection();
    }

    public function getBundle()
    {
        return $this->bundle;
    }

    public function setBundle($bundle)
    {
        $this->bundle = $bundle;
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    public function getMessages()
    {
        return $this->messages->toArray();
    }

    public function setMessages(array $messages)
    {
        $this->messages->clear();

        foreach ($messages as $message) {
            $this->mergeMessage($message);
        }
    }

    public function countMessages()
    {
        return $this->messages->count();
    }

    public function walkMessages($callback)
    {
        return array_walk($this->messages->toArray(), $callback);
    }

    public function mergeMessages(array $messages)
    {
        foreach ($messages as $message) {
            $this->mergeMessage($message);
        }
    }

    public function mergeMessage(Message $message)
    {
        foreach ($this->messages as $index => $innerMessage) {
            if ($innerMessage->isLike($message)) {
                $this->messages[$index]->setParameters($message->getParameters());
                $this->messages[$index]->setIsChoice($message->getIsChoice());
                $this->messages[$index]->mergeTranslations($message->getTranslations());
                return;
            }
        }

        $this->messages->add($message);
    }

    public function isLike(Domain $domain)
    {
        return $domain->getBundle() === $this->bundle && $domain->getDomain() === $this->domain;
    }
}