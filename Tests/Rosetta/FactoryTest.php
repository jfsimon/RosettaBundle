<?php

namespace BeSimple\RosettaBundle\Tests\Rosetta;

use BeSimple\RosettaBundle\Tests\WebTestCase;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class FactoryTest extends WebTestCase
{
    /**
     * @dataProvider provideClassesData
     */
    public function testClasses($config, $method, $adapter, $expectedClassname)
    {
        self::createClient($config);

        $object = static::$kernel
             ->getContainer()
             ->get('be_simple_rosetta.factory')
             ->$method($adapter)
        ;

        $this->assertEquals($expectedClassname, get_class($object));
    }

    public function provideClassesData()
    {
        $configs = array('minimalist', 'scalars');

        $tests = array(
            // loaders
            array('getLoader', 'xliff', 'Symfony\\Component\\Translation\\Loader\\XliffFileLoader'),
            array('getLoader', 'yml',   'Symfony\\Component\\Translation\\Loader\\YamlFileLoader'),
            // dumpers
            array('getDumper', 'xliff', 'BeSimple\\RosettaBundle\\Translation\\Dumper\\XliffFileDumper'),
            array('getDumper', 'yml',   'BeSimple\\RosettaBundle\\Translation\\Dumper\\YamlFileDumper'),
            array('getDumper', 'php',   'BeSimple\\RosettaBundle\\Translation\\Dumper\\PhpFileDumper'),
            array('getDumper', 'csv',   'BeSimple\\RosettaBundle\\Translation\\Dumper\\CsvFileDumper'),
            // scanners
            array('getScanner', 'php.trans',         'BeSimple\\RosettaBundle\\Translation\\Scanner\\Php\\PhpTransScanner'),
            array('getScanner', 'php.trans_choice',  'BeSimple\\RosettaBundle\\Translation\\Scanner\\Php\\PhpTransChoiceScanner'),
            array('getScanner', 'twig.trans',        'BeSimple\\RosettaBundle\\Translation\\Scanner\\Twig\\TwigTransScanner'),
            array('getScanner', 'twig.trans_choice', 'BeSimple\\RosettaBundle\\Translation\\Scanner\\Twig\\TwigTransChoiceScanner'),
            // translators
            array('getTranslator', 'google', 'BeSimple\\RosettaBundle\\Translation\\Webservice\\GoogleTranslator'),
            // parameters guessers
            array('getParametersGuesser', 'glob',    'BeSimple\\RosettaBundle\\Translation\\ParametersGuesser\\GlobParametersGuesser'),
            array('getParametersGuesser', 'regexp',  'BeSimple\\RosettaBundle\\Translation\\ParametersGuesser\\RegexpParametersGuesser'),
            array('getParametersGuesser', 'wrapper', 'BeSimple\\RosettaBundle\\Translation\\ParametersGuesser\\ParametersGuesserWrapper'),
        );

        $data = array();
        foreach ($configs as $config) {
            foreach ($tests as $test) {
                array_unshift($test, $config);
                $data[] = $test;
            }
        }

        return $data;
    }
}
