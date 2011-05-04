<?php

namespace BeSimple\RosettaBundle\Entity;

use BeSimple\RosettaBundle\Model\ModelFactoryInterface;
use BeSimple\RosettaBundle\Model\Domain as BaseDomain;
use BeSimple\RosettaBundle\Model\Message as BaseMessage;

class EntityFactory implements ModelFactoryInterface
{
    public function domain($bundle = null, $domain = null)
    {
        return new Domain($bundle, $domain);
    }

    public function message(BaseDomain $domain = null, $text = null)
    {
        return new Domain($domain, $text);
    }

    public function translation(BaseMessage $message = null, $locale = null, $text = null)
    {
        return new Domain($message, $locale, $text);
    }
}