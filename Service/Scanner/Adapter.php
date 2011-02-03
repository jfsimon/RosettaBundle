<?php

namespace Bundle\RosettaBundle\Service\Scanner;

class Adapter
{
    protected $messages;

    public function __construct()
    {
        $this->messages = array();
    }

    public function scanFile($file, $bundle = null)
    {
        $this->scan(file_get_contents($file), $bundle);
    }

    public function scan($content, $bundle = null)
    {
        foreach($this->parseMessages($content) as $message) {
            if(is_array($message)) {
                $message['bundle'] = $bundle;
                $this->messages[] = $message;
            }
        }
    }

    public function getMessages($bundle = null)
    {
        return $this->messages;
    }
}