<?php

namespace BeSimple\RosettaBundle\Model;

class DomainManager extends Manager
{
    public function get($bundleName, $domainName)
    {
        return $this->repository->findOneBy(array('bundle' => $bundleName, 'domain' => $domainName))
                ? : $this->factory->domain($bundleName, $domainName);
    }

    public function save(Domain $domain)
    {
        $this->repository->persist($domain);
        $this->repository->flush();
    }

    public function saveCollection(DomainCollection $domains)
    {
        foreach ($domains as $domain) {
            $this->repository->persist($domain);
        }

        $this->repository->flush();
    }

    public function findByBundleName($bundleName)
    {
        return $this->repository->findBy(array('bundle' => $bundleName));
    }

    public function findByDomainName($domainName)
    {
        return $this->repository->findBy(array('domain' => $domainName));
    }
}
