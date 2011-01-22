<?php

namespace Bundle\RosettaBundle\Service\Scanner;

class BaseScanner implements \IteratorAggregate
{
    protected $messages;
    protected $content;

    public function __construct()
    {
        $this->messages = array();
    }

    public function loadFile($file)
    {
        $content = file_get_contents($file);

        foreach($this->parseMessages($content) as $message) {
            if(is_array($message)) {
                $this->messages[] = $message;
            }
        }
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