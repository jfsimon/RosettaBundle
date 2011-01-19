<?php

namespace Bundle\RosettaBundle\Model\Repository;

use Bundle\RosettaBundle\Model\Entity\Message;
use Bundle\RosettaBundle\Model\Entity\Domain;

class TranslationRepository extends Repository
{
    public function getOrCreate(Domain $domain, $text)
    {
        $message = $this->findOneBy(array('hash' => Message::hash($text), 'domain' => $domain));
        return $message ? $message : new Message($text, $domain);
    }
}