<?php

namespace BeSimple\RosettaBundle\Entity;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;

abstract class Repository
{
    protected $entityManager;
    protected $entityName;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function persist(Model $model)
    {
        $model->setUpdatedAt(new \DateTime());
        $this->entityManager->persist($model);
    }

    public function flush()
    {
        $this->entityManager->flush();
    }

    public function findBy(array $criteria, $joins = true)
    {
        return $qb->buildQuery($criteria, $joins)->getResult();
    }

    public function findOneBy(array $criteria, $joins = true)
    {
        return $qb->buildQuery($criteria, $joins)->getSingleResult();
    }

    public function find($id, $joins = true)
    {
        return $qb->buildQuery(array('id' => $id), $joins)->getSingleResult();
    }

    public function getEntityManager()
    {
        return $this->entityManager;
    }

    public function getEntityName()
    {
        return $this->entityName;
    }

    public function getDoctrineRepository()
    {
        return $this->entityManager->getRepository($this->entityName);
    }

    public function createQueryBuidler($alias)
    {
        return $this
        ->entityManager
                ->createQueryBuilder()
                ->select($alias)
                ->from($this->entityName, $alias);
    }

    public function buildQuery(array $criteria, $joins = true)
    {
        $qb = $this->createQueryBuidler('r');
        $qb = $this->addQueryCriteria($qb, $criteria);

        if ($joins) {
            $qb = $this->addQueryJoins($qb);
        }

        return $qb->getQuery();
    }

    protected function addQueryCriteria(QueryBuilder $qb, array $criteria)
    {
        foreach ($criteria as $field => $value) {
            $qb->where(sprintf('r.%s = :%s', $field, $field));
            $qb->setParameter($field, $value);
        }

        return $qb;
    }

    abstract protected function addQueryJoins(QueryBuilder $qb);
}
