<?php

namespace BeSimple\RosettaBundle\Model;

abstract class Model
{
    protected $id;

    /**
     * Creation datetime.
     *
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * Last update datetime.
     *
     * @var \DateTime
     */
    protected $updatedAt;

    public function getId()
    {
        return $this->id;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $datetime)
    {
        $this->updatedAt = $datetime;
    }

    public static function hash($text)
    {
        return sha1($text);
    }
}