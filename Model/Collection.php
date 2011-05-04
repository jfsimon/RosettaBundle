<?php

namespace BeSimple\RosettaBundle\Model;

abstract class Collection implements \IteratorAggregate, \Countable
{
    protected $children;

    public function __construct()
    {
        $this->children = array();
    }

    public function clear()
    {
        $this->children = array();
    }

    public function count()
    {
        return count($this->children);
    }

    public function walk($callback)
    {
        return array_walk($this->children, $callback);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->children);
    }

    public function toArray()
    {
        return $this->children;
    }

    protected function addChild($child)
    {
        $key = array_search($child, $this->children);

        if ($key === false) {
            $this->children[] = $child;
        }

        $this->children[$key] = $child;
    }

    public function hasChild($child)
    {
        return array_search($child, $this->children) !== false;
    }

    public function removeChild($child)
    {
        $key = array_search($child, $this->children);

        if ($key === false) {
            return false;
        }

        unset($this->children[$key]);
        return true;
    }
}