<?php

namespace BeSimple\RosettaBundle\Tests\Model;

use BeSimple\RosettaBundle\Tests\TestCase;
use BeSimple\RosettaBundle\Entity\Helper\Helper;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class HelperTest extends TestCase
{
    /**
     * @dataProvider provideKeyTexts
     */
    public function testKeyText($text)
    {
        $helper = new Helper();

        $this->assertTrue($helper->isKey($text));
    }

    /**
     * @dataProvider provideNonKeyTexts
     */
    public function testNonKeyText($text)
    {
        $helper = new Helper();

        $this->assertFalse($helper->isKey($text));
    }

    public function provideKeyTexts()
    {
        return array(
            array('symfony.is.great'),
            array('hello.world'),
            array('key.1'),
        );
    }

    public function provideNonKeyTexts()
    {
        return array(
            array('Symfony is great'),
            array('Hello world!'),
            array('key1'),
        );
    }
}
