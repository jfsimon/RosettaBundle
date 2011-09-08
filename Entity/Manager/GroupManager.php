<?php

namespace BeSimple\RosettaBundle\Entity\Manager;

use BeSimple\RosettaBundle\Entity\Group;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class GroupManager extends AbstractManager
{
    const ENTITY_CLASS = '\\BeSimple\\RosettaBundle\\Entity\\Group';

    /**
     * Creates a managed group.
     *
     * @param string $bundle A bundle name
     * @param string $domain A domain name
     *
     * @return Group
     */
    public function create($bundle, $domain)
    {
        $group = new Group($bundle, $domain);
        $this->manage($group);

        return $group;
    }

    /**
     * Manages a group.
     *
     * @param Group $group A group to manage
     *
     * @return GroupManager This instance
     */
    public function manage(Group $group)
    {
        $this->manager->persist($group);

        return $this;
    }

    /**
     * Removes a group.
     *
     * @param Group $group A group to delete
     *
     * @return GroupManager This instance
     */
    public function remove(Group $group)
    {
        $this->manager->remove($group);

        return $this;
    }

    /**
     * Finds groups by bundle name.
     *
     * @return array An array of groups
     */
    public function findByBundle($bundle)
    {
        return $this->findBy(array('bundle' => $bundle));
    }

    /**
     * Finds groups by domain name.
     *
     * @return array An array of groups
     */
    public function findByDomain($domain)
    {
        return $this->findBy(array('domain' => $domain));
    }

    /**
     * Finds one group by bundle name and domain name.
     *
     * @param string $bundle A bundle name
     * @param string $domain A domain name
     *
     * @return Group|null A group or null
     */
    public function findOneByBundleAndDomain($bundle, $domain)
    {
        return $this->findOneBy(array('bundle' => $bundle, 'domain' => $domain));
    }
}
