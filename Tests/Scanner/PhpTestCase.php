<?php

namespace Bundle\RosettaBundle\Tests\Scanner;

use Bundle\RosettaBundle\Service\Scanner\PhpScanner;

class PhpTestCase extends BaseTestCase
{
    protected function scanFile($file)
    {
        $scanner = new PhpScanner();
        $scanner->scanFile($this->getTestFile($file.'.php'));
        return $scanner->getMessages();
    }
}