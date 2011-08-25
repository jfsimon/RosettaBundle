<?php

namespace BeSimple\RosettaBundle\Model;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
interface GroupManagerInterface
{
    /**
     * Creates a managed group.
     *
     * @param string $bundle A bundle name
     * @param string $domain A domain name
     *
     * @return Group
     */
    function create($bundle, $domain);

    /**
     * Manages a group.
     *
     * @param Group $group A group to manage
     */
    function manage(Group $group);

    /**
     * Deletes a group.
     *
     * @param Group $group A group to delete
     */
    function delete(Group $group);

    /**
     * Applies changes in database.
     */
    function flush();

    /**
     * Finds all groups from database.
     *
     * @return array An array of groups
     */
    function findAll();

    /**
     * Finds groups by criteria.
     *
     * @param array $criteria
     *
     * @return array An array of groups
     */
    function findBy(array $criteria);

    /**
     * Finds one group by criteria.
     *
     * @param array $criteria
     *
     * @return Group|null A group or null
     */
    function findOneBy(array $criteria);

    /**
     * Finds groups by bundle name.
     *
     * @return array An array of groups
     */
    function findByBundle($bundle);

    /**
     * Finds one group by bundle name and domain name.
     *
     * @param string $bundle Bundle name
     * @param string $domain Domain name
     *
     * @return Group|null A group or null
     */
    function findOneByBundleAndDomain($bundle, $domain);
}
