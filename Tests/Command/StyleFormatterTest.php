<?php

namespace BeSimple\RosettaBundle\Tests\Model;

use BeSimple\RosettaBundle\Tests\TestCase;
use BeSimple\RosettaBundle\Entity\Helper;
use BeSimple\RosettaBundle\Command\Formatter\StyleFormatter;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class StyleFormatterTest extends TestCase
{
    /**
     * @dataProvider provideFlattenTests
     */
    public function testFlatten($nested, $flatten)
    {
        $formatter = new StyleFormatter();

        $this->assertEquals($flatten, $formatter->flatten($nested));
    }

    public function provideFlattenTests()
    {
        return array(
            array('a', 'a'),
            array('<a></a>a', 'a'),
            array('a<a>b</a>c', 'a<a>b</a>c'),
            array('a<a>b</a>c<b>d</b>e', 'a<a>b</a>c<b>d</b>e'),
            array('a<a><b>b</b></a>c', 'a<b>b</b>c'),
            array('a<a>b<b>c</b>d</a>e', 'a<a>b</a><b>c</b><a>d</a>e'),
            array('<a>b<b>c</b>d</a>', '<a>b</a><b>c</b><a>d</a>'),
        );
    }
}
