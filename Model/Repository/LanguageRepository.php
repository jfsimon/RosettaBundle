<?php

namespace Bundle\RosettaBundle\Model\Repository;

use Bundle\RosettaBundle\Model\Entity\Language;

class MessageRepository extends Repository
{
    public function getOrCreate($code)
    {
        $language = $this->findOneBy(array('code' => $code));
        return $language ? $language : new Language($code);
    }
}