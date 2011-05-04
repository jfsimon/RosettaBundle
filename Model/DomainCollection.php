<?php

namespace BeSimple\RosettaBundle\Model;

class DomainCollection extends Collection implements \IteratorAggregate, \Countable
{
    public function add(Domain $domain)
    {
        $this->addChild($domain);
    }

    public function has(Domain $domain)
    {
        return $this->hasChild($domain);
    }

    public function merge(Domain $domain)
    {
        foreach ($this as $index => $innerDomain) {
            if ($innerDomain->isLike($domain)) {
                $this->children[$index]->mergeMessages($domain->getMessages());
                return;
            }
        }

        $this->addChild($domain);
    }

    public function walkMessages($callback)
    {
        foreach ($this as $domain) {
            $domain->walkMessages($callback);
        }
    }

    public function getMessages()
    {
        $messages = array();

        foreach ($this as $domain) {
            $messages = array_merge($messages, $domain->getMessages());
        }

        return $messages;
    }
}