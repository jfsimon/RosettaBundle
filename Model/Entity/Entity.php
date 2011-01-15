<?php

namespace Bundle\RosettaBundle\Model\Entity;

abstract class Entity
{
    public function offsetExists($offset) {
        return isset($this->$offset);
    }

    public function offsetSet($offset, $value) {
        throw new \BadMethodCallException('Array access of class '.get_class($this).' is read-only!');
    }

    public function offsetGet($offset) {
        $method = 'get'.ucfirst($offset);
        return $this->$method();
    }

    public function offsetUnset($offset) {
        throw new \BadMethodCallException('Array access of class '.get_class($this).' is read-only!');
    }

}