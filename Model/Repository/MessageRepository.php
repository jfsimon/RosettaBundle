<?php

namespace Bundle\RosettaBundle\Model\Repository;

use Bundle\RosettaBundle\Model\Entity\Translation;
use Bundle\RosettaBundle\Model\Entity\Message;
use Bundle\RosettaBundle\Model\Entity\Language;

class TranslationRepository extends Repository
{
    public function getOrCreate($text, $domain=null)
    {
        if(is_null($domain) || is_string($domain)) {
            $repository = new DomainRepository();
            $domain = $repository->getOrCreate($domain);
        }

        $message = $this->findOneBy(array('hash' => Message::hash($text), 'domain' => $domain));
        return $message ? $message : new Message($text, $domain);
    }
}