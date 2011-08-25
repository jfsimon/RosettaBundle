<?php

namespace BeSimple\RosettaBundle\Model;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class GroupCollection implements \IteratorAggregate, \Countable
{
    const GLUE = '|';

    /**
     * @var array
     */
    protected $groups;

    /**
     * Constructor.
     *
     * @param array $groups An array of group
     */
    public function __construct(array $groups = array())
    {
        $this->groups = $groups;
    }

    /**
     * Find a group by bundle name and domain name.
     *
     * @param string $bundle A bundle name
     * @param string $domain A domain name
     *
     * @return Group|null
     */
    public function find($bundle, $domain)
    {
        return isset($this->groups[$bundle.self::GLUE.$domain]) ? $this->groups[$bundle.self::GLUE.$domain] : null;
    }

    /**
     * Removes a group by bundle name and domain name.
     *
     * @param string $bundle A bundle name
     * @param string $domain A domain name
     *
     * @return GroupCollection
     */
    public function remove($bundle, $domain)
    {
        unset($this->groups[$bundle.self::GLUE.$domain]);

        return $this;
    }

    /**
     * Adds a group to collection.
     *
     * @param Group $group A Group instance
     *
     * @return GroupCollection This instance
     */
    public function add(Group $group)
    {
        $this->groups[$group->getBundle().self::GLUE.$group->getDomain()] = $group;

        return $this;
    }

    /**
     * Returns an array of all groups in collection.
     *
     * @return array An array of groups
     */
    public function all()
    {
        return $this->groups;
    }

    /**
     * Returns count of groups in the collection.
     *
     * @return int
     */
    public function count()
    {
        return count($this->groups);
    }

    /**
     * Returns an iterator.
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->groups);
    }
}
