<?php

namespace BeSimple\RosettaBundle\Translation\Dumper;

use BeSimple\RosettaBundle\Entity\Helper;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class XliffFileDumper extends AbstractFileDumper implements DumperInterface
{
    /**
     * @var string
     */
    protected $sourceLocale;

    /**
     * Constructor.
     *
     * @param MessageHelper $helper       A MessageHelper instance
     * @param string|null   $sourceLocale Source messages locale
     */
    public function __construct(Helper $helper, $sourceLocale = null)
    {
        parent::__construct($helper);
        $this->sourceLocale = $sourceLocale;
    }

    /**
     * {@inheritdoc}
     */
    public function dump($resource, array $messages)
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $body = $dom->createElement('body');

        $id = 1;
        foreach ($messages as $key => $val) {
            $source = $dom->createElement('source');
            $source->nodeValue = $key;

            $target = $dom->createElement('target');
            $target->nodeValue = $val;

            $unit = $dom->createElement('trans-unit');
            $unit->setAttribute('id', (string) $id ++);
            $unit->appendChild($source);
            $unit->appendChild($target);

            $body->appendChild($unit);
        }

        $file = $dom->createElement('file');
        $file->setAttribute('source-language', $this->sourceLocale);
        $file->setAttribute('original', 'file.ext');
        $file->setAttribute('datatype', 'plaintext');
        $file->appendChild($body);

        $xliff = $dom->createElement('xliff');
        $xliff->setAttribute('version', '1.2');
        $xliff->setAttribute('xmlns', 'urn:oasis:names:tc:xliff:document:1.2');
        $xliff->appendChild($file);

        $dom->appendChild($xliff);

        $this->write($resource, $dom->saveXML());
    }

    /**
     * @param string $sourceLocale
     */
    public function setSourceLocale($sourceLocale)
    {
        $this->sourceLocale = $sourceLocale;
    }

    /**
     * @return string
     */
    public function getSourceLocale()
    {
        return $this->sourceLocale;
    }
}
