<?php

namespace Bundle\RosettaBundle\Tests;

class RosettaTestSuite extends \PHPUnit_Framework_TestSuite
{
    public static function suite()
    {
        $namespace = 'Bundle\\RosettaBundle\\Tests';
        $suite = new self('Rosetta tests');

        $suite->addTestSuite($namespace.'\\Scanner\\PhpTestCase');
        $suite->addTestSuite($namespace.'\\Locator\\GuessBundleTestCase');
        $suite->addTestSuite($namespace.'\\Locator\\LocateBundleTestCase');

        return $suite;
    }
}