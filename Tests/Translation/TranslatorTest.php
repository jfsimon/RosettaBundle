<?php

namespace BeSimple\RosettaBundle\Tests\Translation;

use BeSimple\RosettaBundle\Tests\TestCase;
use BeSimple\RosettaBundle\Translation\Webservice\TranslatorInterface;
use BeSimple\RosettaBundle\Translation\Webservice\GoogleTranslator;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class TranslatorTest extends TestCase
{
    protected $fromLocale = 'en';
    protected $toLocales  = array('fr', 'es', 'de');

    /**
     * @dataProvider provideTranslateData
     */
    public function testTranslate(TranslatorInterface $translator, array $texts, array $expected)
    {
        foreach ($texts as $index => $text) {
            $translation = $translator->translate($text, $this->toLocales, $this->fromLocale);
            $this->assertEquals($this->cleanup($expected[$index]), $this->cleanup($translation));
        }
    }

    /**
     * @dataProvider provideTranslateData
     */
    public function testTranslateBatch(TranslatorInterface $translator, array $texts, array $expected)
    {
        $translations = $translator->translateBatch($texts, $this->toLocales, $this->fromLocale);
        $this->assertEquals($this->cleanup($expected), $this->cleanup($translations));
    }

    public function provideTranslateData()
    {
        $translators = array(
            new GoogleTranslator(),
        );

        $texts = array('keyboard', 'coffee', 'music');

        $expected = array(
            array('fr' => 'clavier', 'es' => 'teclado', 'de' => 'tastatur'),
            array('fr' => 'café',    'es' => 'café',    'de' => 'kaffee'),
            array('fr' => 'musique', 'es' => 'música',  'de' => 'musik'),
        );

        $data = array();
        foreach ($translators as $translator) {
            $data[] = array($translator, $texts, $expected);
        }

        return $data;
    }

    protected function cleanup(array $data)
    {
        // returned translations have various cases
        return array_walk_recursive($data, function($value) {
            return strtolower($value);
        });
    }
}
