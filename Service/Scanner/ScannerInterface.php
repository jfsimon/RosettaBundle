<?php

namespace Bundle\RosettaBundle\Service\Scanner;

interface ScannerInterface
{
    public function loadFile($file);
    public function getMessages();
}