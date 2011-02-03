<?php

namespace Bundle\RosettaBundle\Service\Scanner;

interface AdapterInterface
{
    public function scanFile($file, $bundle = null);
    public function scan($content, $bundle = null);
    public function getMessages();
}