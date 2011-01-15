<?php

namespace Bundle\RosettaBundle\Model\Repository;

use Bundle\RosettaBundle\Model\Entity\Domain;

class DomainRepository extends Repository
{
    public function getOrCreate($bundle, $name='messages')
    {
        $domain = $this->findOneBy(array('bundle' => $bundle, 'name' => $name));
        return $domain ? $domain : new Domain($name);
    }

    public function getActiveDomains()
    {
        return $this->createQueryBuilder('d')
            ->where('where count(d.messages) > 0')
            ->getQuery()
            ->getResult();
    }
}