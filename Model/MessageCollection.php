<?php

namespace BeSimple\RosettaBundle\Model;

class MessageCollection extends Collection implements \IteratorAggregate, \Countable
{
    public function add(Message $message)
    {
        $this->addChild($message);
    }

    public function has(Message $message)
    {
        return $this->hasChild($message);
    }

    public function walkTranslations($callback)
    {
        foreach ($this as $message) {
            $message->walkTranslations($callback);
        }
    }

    public function getTranslations()
    {
        $translations = array();

        foreach ($this as $message) {
            $translations = array_merge($translations, $message->getTranslations());
        }

        return $translations;
    }
}