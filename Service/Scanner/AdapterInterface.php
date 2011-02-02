<?php

namespace Bundle\RosettaBundle\Service\Scanner;

interface AdapterInterface
{
    public function scanFile($file);
    public function scan($content);
    public function getMessages();
}