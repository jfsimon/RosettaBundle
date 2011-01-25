<?php

namespace Bundle\RosettaBundle\Tests\Locator;

class LocateBundleTestCase extends BaseTestCase
{
    public function test()
    {
        $locator = $this->buildLocator();
        $dir = $locator->locateBundle('Bundle\\RosettaBundle');
        $this->assertEquals(realpath(__DIR__.'/../../'), $dir);
    }
}