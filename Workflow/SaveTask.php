<?php

namespace BeSimple\RosettaBundle\Workflow;

use BeSimple\RosettaBundle\Model\DomainCollection;
use BeSimple\RosettaBundle\Model\DomainManager;

class StoreTask implements TaskInterface
{
    private $domainManager;

    public function __construct(DomainManager $domainManager)
    {
        $this->domainManager = $domainManager;
    }

    public function run(DomainCollection $domains)
    {
        $this->domainManager->saveCollection($domains);

        return $domains;
    }
}