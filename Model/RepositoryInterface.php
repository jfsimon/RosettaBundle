<?php

namespace BeSimple\RosettaBundle\Model;

interface RepositoryInterface
{
    public function persist(Model $model);

    public function flush();

    public function findBy(array $criteria);

    public function findOneBy(array $criteria);

    public function find($id);
}
