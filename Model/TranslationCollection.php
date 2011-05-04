<?php

namespace BeSimple\RosettaBundle\Model;

class TranslationCollection extends Collection implements \IteratorAggregate, \Countable
{
    public function add(Translation $translation)
    {
        $this->addChild($translation);
    }

    public function has(Translation $translation)
    {
        return $this->hasChild($translation);
    }
}