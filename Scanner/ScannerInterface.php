<?php

namespace Bundle\RosettaBundle\Scanner;

interface ScannerInterface
{
    public function scanFile($file);
    public function getMessages();
}