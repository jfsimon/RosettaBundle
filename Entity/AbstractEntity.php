<?php

namespace BeSimple\RosettaBundle\Entity;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
abstract class AbstractEntity
{
    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var \DateTime
     */
    protected $updatedAt;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $updated_at
     *
     * @return AbstractEntity
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return AbstractEntity
     */
    public function notifyUpdate()
    {
        $this->updatedAt = new \DateTime();

        return $this;
    }
}
