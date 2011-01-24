<?php

namespace Bundle\RosettaBundle\Service\Scanner;

class BaseScanner
{
    protected $messages;

    public function __construct()
    {
        $this->messages = array();
    }

    public function scanFile($file)
    {
        $this->scan(file_get_contents($file));
    }

    public function scan($content)
    {
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
}