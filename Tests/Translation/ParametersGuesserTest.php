<?php

namespace BeSimple\RosettaBundle\Tests\Translation;

use BeSimple\RosettaBundle\Tests\TestCase;
use BeSimple\RosettaBundle\Translation\ParametersGuesser;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class ParametersGuesserTest extends TestCase
{
    /**
     * @dataProvider provideGuesses
     */
    public function testGuess(ParametersGuesser\ParametersGuesserInterface $guesser, $message, array $expectedParameters)
    {
        $this->assertEquals($guesser->guess($message), $expectedParameters);
    }

    /**
     * @dataProvider provideValidations
     */
    public function testValidate(ParametersGuesser\ParametersGuesserInterface $guesser, $message, array $parameters, array $expectedParameters)
    {
        $this->assertEquals($guesser->validate($message, $parameters), $expectedParameters);
    }

    public function provideGuesses()
    {
        $data = array();
        foreach ($this->provideGuessers() as $guesser) {
            $data[] = array($guesser, 'I love {{ what }}',     array('{{ what }}'));
            $data[] = array($guesser, 'I love %what%',         array('%what%'));
            $data[] = array($guesser, '{{ who }} love %what%', array('{{ who }}', '%what%'));
        }

        return $data;
    }

    public function provideValidations()
    {
        $data = array();
        foreach ($this->provideGuessers() as $guesser) {
            $data[] = array($guesser, 'I love {{ what }}',     array('{{ what }}'), array('{{ what }}'));
            $data[] = array($guesser, 'I love Symfony2',       array('%what%'),     array());
            $data[] = array($guesser, '{{ who }} love %what%', array('{{ who }}'),  array('{{ who }}'));
        }

        return $data;
    }

    private function provideGuessers()
    {
        return array(
            new ParametersGuesser\RegexpParametersGuesser(array('/{{[^}}]+}}/i', '/%[^%]+%/i')),
            new ParametersGuesser\GlobParametersGuesser(array('{{*}}', '%*%')),
            new ParametersGuesser\ParametersGuesserWrapper(array(
                new ParametersGuesser\RegexpParametersGuesser(array('/{{[^}}]+}}/i')),
                new ParametersGuesser\GlobParametersGuesser(array('%*%')),
            )),
        );
    }
}
