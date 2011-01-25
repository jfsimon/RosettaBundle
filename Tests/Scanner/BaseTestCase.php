<?php

namespace Bundle\RosettaBundle\Tests\Scanner;

use Bundle\RosettaBundle\Tests\RosettaTestCase;

abstract class BaseTestCase extends RosettaTestCase
{
    public function testTrans()
    {
        $messages = $this->scanFile('trans_template');
        $this->transMessagesAssertions($messages);

        for($i = 0; $i < 6; $i++) {
            $this->assertFalse($messages[$i]['choice']);
        }
    }

    public function testTransChoice()
    {
        $messages = $this->scanFile('trans_choice_template');
        $this->transMessagesAssertions($messages);

        for($i = 0; $i < 6; $i++) {
            $this->assertTrue($messages[$i]['choice']);
        }
    }

    public function testUglyTrans()
    {
        $messages = $this->scanFile('ugly_trans_template');

        for($i = 0; $i < 6; $i++) {
            $this->assertEquals('{{ what }} {{ who }}!', $messages[$i]['text']);
            $this->assertEquals(array('{{ what }}', '{{ who }}'), $messages[$i]['parameters']);
            $this->assertEquals('tests', $messages[$i]['domain']);
        }

        for($i = 0; $i < 3; $i++) {
            $this->assertFalse($messages[$i]['choice']);
        }

        for($i = 3; $i < 6; $i++) {
            $this->assertTrue($messages[$i]['choice']);
        }
    }

    abstract protected function scanFile($file);

    protected function transMessagesAssertions(array $messages)
    {
        $this->assertEquals(6, count($messages));

        $this->assertEquals('Hello translation!', $messages[0]['text']);
        $this->assertEquals('Hello translation!', $messages[3]['text']);

        $this->assertEquals('Hello {{ who }}!', $messages[1]['text']);
        $this->assertEquals('Hello {{ who }}!', $messages[4]['text']);

        $this->assertEquals('{{ what }} {{ who }}!', $messages[2]['text']);
        $this->assertEquals('{{ what }} {{ who }}!', $messages[5]['text']);

        $this->assertEquals(array(), $messages[0]['parameters']);
        $this->assertEquals(array(), $messages[3]['parameters']);

        $this->assertEquals(array('{{ who }}'), $messages[1]['parameters']);
        $this->assertEquals(array('{{ who }}'), $messages[4]['parameters']);

        $this->assertEquals(array('{{ what }}', '{{ who }}'), $messages[2]['parameters']);
        $this->assertEquals(array('{{ what }}', '{{ who }}'), $messages[5]['parameters']);

        for($i = 0; $i < 3; $i++) {
            $this->assertEquals('messages', $messages[$i]['domain']);
        }

        for($i = 3; $i < 6; $i++) {
            $this->assertEquals('tests', $messages[$i]['domain']);
        }
    }
}