<?php

namespace Bundle\RosettaBundle\Tests;

require_once __DIR__.'/../../../../app/AppKernel.php';

use AppKernel;
use Bundle\RosettaBundle\Service\Locator\Locator;

abstract class RosettaTestCase extends \PHPUnit_Framework_TestCase
{
    protected function getTestFile($file)
    {
        return realpath(__DIR__.'/../Resources/tests').'/'.$file;
    }

    protected function buildLocator()
    {
        $kernel = new AppKernel('test', false);
        $kernel->boot();

        $locator = new Locator($kernel);

        return $locator;
    }
}