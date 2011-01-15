<?php

namespace Bundle\RosettaBundle\Model\Entity;

/**
 * @orm:Entity(repositoryClass="Bundle\RosettaBundle\Model\Repository\DomainRepository")
 * @orm:Table(name="rosetta_domains")
 */
class Domain extends Entity
{
    /**
     * @orm:ID
     * @orm:Column(type="integer")
     * @orm:GeneratedValue
     */
    protected $id;

    /** @orm:Column(type="string", length=50) */
    protected $bundle;

    /** @orm:Column(type="string", length=50) */
    protected $name;

    /** @orm:Column(type="text") */
    protected $description;

    /** @orm:OneToMany(targetEntity="Message", mappedBy="domain") */
    protected $messages;

    public function __construct($name, $description='')
    {
        $this->setName($name);
        $this->setDescription($description);
        $this->messages = array();
    }

	public function getId()
    {
        return $this->id;
    }

	public function getBundle()
    {
        return $this->bundle;
    }

	public function getName()
    {
        return $this->name;
    }

	public function getDescription()
    {
        return $this->description;
    }

	public function getMessages()
    {
        return $this->messages;
    }

    public function setBundle($bundle)
    {
        $this->bundle = (string)$bundle;
    }

	public function setName($name)
    {
        $this->name = (string)$name;
    }

	public function setDescription($description)
    {
        $this->description = (string)$description;
    }

    public function addMessage(Message $message)
    {
        $message->setDomain($this);
        $this->messages[] = $message;
    }
}