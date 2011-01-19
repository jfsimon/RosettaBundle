<?php

namespace Bundle\RosettaBundle\Service\Scanner;

interface ScannerInterface
{
    public function scanFile($file);
    public function getMessages();
}