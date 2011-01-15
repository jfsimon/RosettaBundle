<?php

namespace Bundle\RosettaBundle\Model\Entity;

/**
 * @orm:Entity(repositoryClass="Bundle\RosettaBundle\Model\Repository\LanguageRepository")
 * @orm:Table(name="rosetta_languages")
 */
class Language extends Entity
{
    /**
     * @orm:ID
     * @orm:Column(type="integer")
     * @orm:GeneratedValue
     */
    protected $id;

    /** @orm:Column(type="string", length=10) */
    protected $code;

    /** @orm:Column(type="string", length=50) */
    protected $name;

    /** @orm:OneToMany(targetEntity="Translation", mappedBy="language") */
    protected $translations;

    public function __construct($code, $name='')
    {
        $this->setCode($code);
        $this->setName($name);
        $this->translations = array();
    }

	public function getId()
    {
        return $this->id;
    }

	public function getCode()
    {
        return $this->code;
    }

	public function getName()
    {
        return $this->name;
    }

    public function getLabel()
    {
        return $this->name ? $this->name : $this->code;
    }

	public function getTranslations()
    {
        return $this->translations;
    }

	public function setCode($code)
    {
        $this->code = $code;
    }

	public function setName($name)
    {
        $this->name = $name;
    }

    public function addTranslation(Translation $translation)
    {
        $translation->setLanguage($this);
        $this->translations[] = $translation;
    }
}