<?php

namespace BeSimple\RosettaBundle\Model;

class MessageManager extends Manager
{
    public function get(Domain $domain, $text)
    {
        return $this->repository->findOneBy(array('domain' => $domain->getId(), 'hash' => Message::hash($text)))
                ? : $this->factory->message($domain, $text);
    }

    public function save(Message $message)
    {
        $this->repository->persist($message);
        $this->repository->flush();
    }

    public function saveCollection(MessageCollection $messages)
    {
        foreach ($messages as $message) {
            $this->repository->persist($message);
        }

        $this->repository->flush();
    }

    public function findByDomain(Domain $domain)
    {
        return $this->repository->findBy(array('domain' => $domain->getId()));
    }
}
