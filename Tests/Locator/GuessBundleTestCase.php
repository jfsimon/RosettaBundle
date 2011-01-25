<?php

namespace Bundle\RosettaBundle\Tests\Locator;

class GuessBundleTestCase extends BaseTestCase
{
    public function testFromPath()
    {
        $locator = $this->buildLocator();
        $bundle = $locator->guessBundleFromPath(__DIR__);
        $this->assertEquals('RosettaBundle', $bundle);
    }

    public function testFromClass()
    {
        $locator = $this->buildLocator();
        $bundle = $locator->guessBundleFromClass(__CLASS__);
        $this->assertEquals('RosettaBundle', $bundle);
    }
}