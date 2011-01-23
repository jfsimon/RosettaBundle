<?php

namespace Bundle\RosettaBundle\Tests\Scanner;

use Bundle\RosettaBundle\Tests\RosettaTestCase;
use Bundle\RosettaBundle\Service\Scanner\PhpScanner;

class ScannerTestCase extends RosettaTestCase
{
    protected function scanFile($file)
    {
        $config = array(
            'scanners' => array(
                '*.php' => 'Bundle\RosettaBundle\Service\Scanner\PhpScanner',
                '*.twig' => 'Bundle\RosettaBundle\Service\Scanner\TwigScanner',
            )
        );

        $locator = $this->buildLocator();
        $scanner = new PhpScanner($locator, $config);
        $template = $this->getTestFile($file);

        $scanner->loadFile($template);
        return $scanner->getMessages();
    }
}