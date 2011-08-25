<?php

namespace BeSimple\RosettaBundle\Model;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
abstract class GroupManager
{
    /**
     * @var string
     */
    protected $class;

    /**
     * {@inheritdoc}
     */
    public function create($bundle, $domain)
    {
        $group = new $this->class($bundle, $domain);
        $this->manage($group);

        return $group;
    }

    /**
     * {@inheritdoc}
     */
    public function findByBundle($bundle)
    {
        return $this->findBy(array('bundle' => $bundle));
    }

    /**
     * {@inheritdoc}
     */
    public function findByDomain($domain)
    {
        return $this->findBy(array('domain' => $domain));
    }

    /**
     * {@inheritdoc}
     */
    public function findOneByBundleAndDomain($bundle, $domain)
    {
        return $this->findOneBy(array('bundle' => $bundle, 'domain' => $domain));
    }
}
