<?php

namespace Bundle\RosettaBundle\Model\Entity;

use Symfony\Component\Translation\Interval;

/**
 * @orm:Entity(repositoryClass="Bundle\RosettaBundle\Model\Repository\MessageRepository")
 * @orm:Table(name="rosetta_messages")
 */
class Message extends Entity
{
    const CHOICE_MESSAGES_SEPARATOR = '|';

    /**
     * @orm:ID
     * @orm:Column(type="integer")
     * @orm:GeneratedValue
     */
    protected $id;

    /** @orm:Column(type="text") */
    protected $text;

    /** @orm:Column(type="string", length=28) */
    protected $hash;

    /**
     * @orm:ManyToOne(targetEntity="Domain", inversedBy="messages")
     * @orm:JoinColumn(name="domain_id", referencedColumnName="id")
     */
    protected $domain;

    /** @orm:Column(type="boolean", name="is_choice") */
    protected $isChoice;

    /** @orm:Column(type="array") */
    protected $parameters;

    /** @orm:Column(type="boolean") */
    protected $isLive;

    /** @orm:Column(type="datetime", name="created_at") */
    protected $createdAt;

    /**
     * @orm:OneToMany(targetEntity="Translation", mappedBy="message")
     * @orm:OrderBy({"rating"="DESC"})
     */
    protected $translations;

    public function __construct($text, Domain $domain, $isChoice=false, $parameters=array(), $isLive=false)
    {
        $this->setText($text);
        $this->setDomain($domain);
        $this->setIsChoice($isChoice);
        $this->setParameters($parameters);
        $this->setIsLive($isLive);
        $this->createdAt = new \DateTime();
        $this->translations = array();
    }

	public function getId()
    {
        return $this->id;
    }

	public function getText()
    {
        return $this->text;
    }

    public function getChoiceTexts()
    {
        $messages = array();
        $parts = explode(self::CHOICE_MESSAGES_SEPARATOR, $this->text);

        foreach($parts as $part) {
            $part = trim($part);

            if (preg_match('/^(?<interval>'.Interval::getIntervalRegexp().')\s+(?<message>.+?)$/x', $part, $matches)) {
                $messages[$matches['interval']] = $matches['message'];
            } elseif (preg_match('/^(\w+)\: +(.+)$/', $part, $matches)) {
                $messages[$matches[1]] = $matches[2];
            } else {
                $messages[] = $part;
            }
        }

        return $messages;
    }

	public function getDomain()
    {
        return $this->domain;
    }

	public function getIsChoice()
    {
        return $this->isChoice;
    }

	public function getParameters()
    {
        return $this->parameters;
    }

	public function getIsLive()
    {
        return $this->isLive;
    }

	public function getCreatedAt()
    {
        return $this->createdAt;
    }

	public function getTranslations()
    {
        return $this->translations;
    }

	public function setText($text)
    {
        $this->text = (string)$text;
        $this->hash = self::hash($this->text);
    }

	public function setDomain(Domain $domain)
    {
        $this->domain = $domain;
    }

	public function setIsChoice($isChoice)
    {
        $this->isChoice = (bool)$isChoice;
    }

	public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
    }

	public function setIsLive($isLive)
    {
        $this->isLive = (bool)$isLive;
    }

    public function hasParameter($parameter)
    {
        return in_array($parameter, $this->parameters);
    }

    public function addParameter($parameter)
    {
        $this->parameters[] = (string)$parameter;
    }

    public function removeParameter($parameter)
    {
        if($this->hasParameter($parameter)) {
            $key = array_search($parameter, $this->parameters);
            unset($this->parameters[$key]);
        }
    }

    public function addTranslation(Translation $translation)
    {
        $translation->setMessage($this);
        $this->translations[] = $translation;
    }

    public static function hash($text) {
        return sha1($text);
    }
}