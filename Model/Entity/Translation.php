<?php

namespace Bundle\RosettaBundle\Model\Entity;

/**
 * @orm:Entity(repositoryClass="Bundle\RosettaBundle\Model\Repository\TranslationRepository")
 * @orm:Table(name="rosetta_translations")
 */
class Translation extends Entity
{
    /**
     * @orm:ID
     * @orm:Column(type="integer")
     * @orm:GeneratedValue
     */
    protected $id;

    /**
     * @orm:ManyToOne(targetEntity="Message", inversedBy="translations")
     * @orm:JoinColumn(name="message_id", referencedColumnName="id")
     */
    protected $message;

    /**
     * @orm:ManyToOne(targetEntity="Language", inversedBy="translations")
     * @orm:JoinColumn(name="language_id", referencedColumnName="id")
     */
    protected $language;

    /** @orm:Column(type="text") */
    protected $text;

    /** @orm:Column(type="integer") */
    protected $rating;

    /** @orm:Column(type="boolean", name="is_choosen") */
    protected $isChoosen;

    /** @orm:Column(type="boolean", name="is_automatic") */
    protected $isAutomatic;

    /** @orm:Column(type="datetime", name="created_at") */
    protected $createdAt;

    public function __construct(Message $message, Language $language, $text, $rating=0, $isChoosen=false, $isAutomatic=false)
    {
        $this->setMessage($message);
        $this->setLanguage($language);
        $this->setText($text);
        $this->setRating($rating);
        $this->setIsChoosen($isChoosen);
        $this->setIsAutomatic($isAutomatic);
        $this->createdAt = new \DateTime();
    }

	public function getId()
    {
        return $this->id;
    }

	public function getMessage()
    {
        return $this->message;
    }

	public function getLanguage()
    {
        return $this->language;
    }

	public function getText()
    {
        return $this->text;
    }

	public function getRating()
    {
        return $this->rating;
    }

	public function getIsChoosen()
    {
        return $this->isChoosen;
    }

	public function getIsAutomatic()
    {
        return $this->isAutomatic;
    }

	public function getCreatedAt()
    {
        return $this->createdAt;
    }

	public function setMessage(Message $message)
    {
        $this->message = $message;
    }

	public function setLanguage(Language $language)
    {
        $this->language = $language;
    }

	public function setText($text)
    {
        $this->text = (string)$text;
    }

	public function setRating($rating)
    {
        $this->rating = (int)$rating;
    }

	public function setIsChoosen($isChoosen)
    {
        $this->isChoosen = (bool)$isChoosen;
    }

	public function setIsAutomatic($isAutomatic)
    {
        $this->isAutomatic = (bool)$isAutomatic;
    }
}