<?php

namespace BeSimple\RosettaBundle\Model;

abstract class Manager
{
    /**
     * Model repository.
     *
     * @var RepositoryInterface
     */
    protected $repository;

    protected $factory;

    public function __construct(RepostoryInterface $repository, ModelFactoryInterface $factory)
    {
        $this->repository = $repository;
        $this->factory = $factory;
    }

    public function getRepository()
    {
        return $this->repository;
    }
}