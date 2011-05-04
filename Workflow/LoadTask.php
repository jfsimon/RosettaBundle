<?php

namespace BeSimple\RosettaBundle\Workflow;

use BeSimple\RosettaBundle\Model\Domain;
use BeSimple\RosettaBundle\Model\DomainManager;

class LoadTask implements TaskInterface
{
    private $domainManager;

    public function __construct(DomainManager $domainManager)
    {
        $this->domainManager = $domainManager;
    }

    public function run(DomainCollection $domains)
    {
        $loadedDomains = new DomainCollection();

        foreach ($domains as $domain) {
            $loadedDomains->add($this->loadDomain($domain));
        }

        return $loadedDomains;
    }

    private function loadDomain(Domain $domain)
    {
        $loaded = $this->domainManager->get($domain->getBundle(), $domain->getDomain());

        if (!$loaded->getId()) {
            return $domain;
        }

        $loaded->mergeMessages($domain->getMessages());

        return $loaded;
    }
}