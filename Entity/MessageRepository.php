<?php

namespace BeSimple\RosettaBundle\Entity;

use Doctrine\ORM\QueryBuilder;

class MessageRepository
{
    protected $entityManager;
    protected $entityName;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityName = __NAMESPACE__ . '\\Message';
        parent::__construct($entityManager);
    }

    protected function addQueryJoins(QueryBuilder $qb)
    {
        $qb->join('r.translations', 't');

        return $qb;
    }
}
