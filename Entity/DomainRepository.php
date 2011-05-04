<?php

namespace BeSimple\RosettaBundle\Entity;

use Doctrine\ORM\QueryBuilder;

class DomainRepository
{
    protected $entityManager;
    protected $entityName;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityName = __NAMESPACE__ . '\\Domain';
        parent::__construct($entityManager);
    }

    protected function addQueryJoins(QueryBuilder $qb)
    {
        $qb->join('r.messages', 'm');
        $qb->join('m.translations', 't');

        return $qb;
    }
}
