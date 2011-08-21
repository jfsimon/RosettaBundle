<?php

namespace BeSimple\RosettaBundle\Tests\Translation;

use BeSimple\RosettaBundle\Tests\TestCase;
use BeSimple\RosettaBundle\Translation\Scanner\ScannerInterface;
use BeSimple\RosettaBundle\Translation\Scanner\Php\PhpTransScanner;
use BeSimple\RosettaBundle\Translation\Scanner\Php\PhpTransChoiceScanner;
use BeSimple\RosettaBundle\Translation\Scanner\Twig\TwigTransScanner;
use BeSimple\RosettaBundle\Translation\Scanner\Twig\TwigTransChoiceScanner;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class ScannerTest extends TestCase
{
    /**
     * @dataProvider provideScannerData
     */
    public function testScanner(ScannerInterface $scanner, $content, $isChoice)
    {
        $messages = $scanner->scan($content);

        $this->assertEquals(6, count($messages));

        $this->assertEquals('I love Symfony2!', $messages[0]['text']);
        $this->assertEquals('I love Symfony2!', $messages[3]['text']);

        $this->assertEquals('I love %what%!', $messages[1]['text']);
        $this->assertEquals('I love %what%!', $messages[4]['text']);

        $this->assertEquals('%who% love %what%!', $messages[2]['text']);
        $this->assertEquals('%who% love %what%!', $messages[5]['text']);

        $this->assertEquals(null, $messages[0]['parameters']);
        $this->assertEquals(null, $messages[3]['parameters']);

        $this->assertEquals(array('%what%'), $messages[1]['parameters']);
        $this->assertEquals(array('%what%'), $messages[4]['parameters']);

        $this->assertEquals(array('%who%', '%what%'), $messages[2]['parameters']);
        $this->assertEquals(array('%who%', '%what%'), $messages[5]['parameters']);

        for($i = 0; $i < 3; $i++) {
            $this->assertEquals('messages', $messages[$i]['domain']);
        }

        for($i = 3; $i < 6; $i++) {
            $this->assertEquals('tests', $messages[$i]['domain']);
        }

        for($i = 0; $i < 6; $i++) {
            $this->assertEquals($isChoice, $messages[$i]['isChoice']);
        }
    }

    public function provideScannerData()
    {
        $viewsPath  = realpath(__DIR__.'/../Resources/views/scanner');

        $tests = array(
            //    template                       scanner                        isChoice
            array('trans.html.php',              new PhpTransScanner(),         false),
            array('transChoice.html.php',        new PhpTransChoiceScanner(),   true),
            array('transFilter.html.twig',       new TwigTransScanner(),        false),
            array('transBlock.html.twig',        new TwigTransScanner(),        false),
            array('transChoiceFilter.html.twig', new TwigTransChoiceScanner(),  true),
            array('transChoiceBlock.html.twig',  new TwigTransChoiceScanner(),  true),
        );

        $data = array();
        foreach ($tests as $test) {
            $data[] = array($test[1], file_get_contents($viewsPath.'/'.$test[0]), $test[2]);
        }

        return $data;
    }
}
