<?php

namespace Bundle\RosettaBundle\Tests\Scanner;

use Bundle\RosettaBundle\Service\Scanner\TwigScanner;

class TwigTestCase extends BaseTestCase
{
    protected function scanFile($file)
    {
        $scanner = new TwigScanner();
        $scanner->scanFile($this->getTestFile($file.'.twig'));
        return $scanner->getMessages();
    }
}