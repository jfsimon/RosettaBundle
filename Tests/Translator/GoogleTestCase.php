<?php

namespace Bundle\RosettaBundle\Tests\Translator;

use Bundle\RosettaBundle\Service\Translator\Translator;

class GoogleTestCase extends BaseTestCase
{
    protected $en = 'Hello translator!';
    protected $fr = 'Bonjour traducteur!';
    protected $de = 'Hallo Übersetzer!';

    public function testV1One()
    {
        $this->assertEquals($this->fr, $this->translate(1, $this->en, 'fr'));
    }

    public function testV2One()
    {
        // Google Translate dont V2 seems to work for now
        //$this->assertEquals($this->fr, $this->translate(2, $this->en, 'fr'));
    }

    public function testV1Many()
    {
        $this->assertEquals(array('fr' => $this->fr, 'de' => $this->de), $this->translate(1, $this->en, array('fr', 'de')));
    }

    public function testV2Many()
    {
        // Google Translate dont V2 seems to work for now
        //$this->assertEquals(array('fr' => $this->fr, 'de' => $this->de), $this->translate(2, $this->en, array('fr', 'de')));
    }

    protected function translate($version, $string, $to)
    {
        $config = array(
            'locale' => 'en',
            'adapter' => 'Bundle\RosettaBundle\Service\Translator\GoogleAdapter',
            'config' => array(
                'version' => $version,
                'key' => null
            )
        );

        $translator = new Translator($config);
        return $translator->translate($string, $to);
    }
}