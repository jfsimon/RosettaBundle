<?php

namespace BeSimple\RosettaBundle\Entity;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
abstract class AbstractManager
{
    /**
     * @var EntityManager
     */
    protected $manager;

    /**
     * @var HelperInterface
     */
    protected $helper;

    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * @param EntityManager   $manager Doctrine entity manager
     * @param HelperInterface $helper  Entity helper
     */
    public function __construct(EntityManager $manager, HelperInterface $helper)
    {
        $this->manager    = $manager;
        $this->helper     = $helper;
        $this->repository = $manager->getRepository(static::ENTITY_CLASS);
    }

    /**
     * Applies changes in database.
     *
     * @return AbstractManager This instance
     */
    public function flush()
    {
        $this->manager->flush();

        return $this;
    }

    /**
     * @see EntityRepository
     */
    public function findAll()
    {
        return $this->repository->findAll();
    }

    /**
     * @see EntityRepository
     */
    public function findBy(array $criteria)
    {
        return $this->repository->findBy($criteria);
    }

    /**
     * @see EntityRepository
     */
    public function findOneBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * Returns the entity helper.
     *
     * @return HelperInterface
     */
    public function getHelper()
    {
        return $this->helper;
    }

    /**
     * Returns the entity manager.
     *
     * @return EntityManager
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * Returns the entity repository.
     *
     * @return EntityRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }
}
