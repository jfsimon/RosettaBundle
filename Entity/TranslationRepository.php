<?php

namespace BeSimple\RosettaBundle\Entity;

use Doctrine\ORM\QueryBuilder;

class TranslationRepository
{
    protected $entityManager;
    protected $entityName;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityName = __NAMESPACE__ . '\\Translation';
        parent::__construct($entityManager);
    }

    protected function addQueryJoins(QueryBuilder $qb)
    {
        return $qb;
    }
}
