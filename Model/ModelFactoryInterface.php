<?php

namespace BeSimple\RosettaBundle\Entity;

class ModelFactoryInterface
{
    public function domain($bundle = null, $domain = null);

    public function message(Domain $domain = null, $text = null);

    public function translation(Message $message = null, $locale = null, $text = null);
}
