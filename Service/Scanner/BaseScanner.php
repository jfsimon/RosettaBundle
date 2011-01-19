<?php

namespace Bundle\RosettaBundle\Service\Scanner;

class BaseScanner implements \IteratorAggregate
{
    protected $messages;

    public function __construct()
    {
        $this->messages = array();
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function getIterator()
    {
        return $this->messages;
    }
}