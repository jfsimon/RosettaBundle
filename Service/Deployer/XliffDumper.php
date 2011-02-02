<?php

namespace Bundle\RosettaBundle\Service\Deployer;

class XliffDumper extends Dumper implements DumperInterface
{
    protected $document;

    public function render(array $translations)
    {
        $this->document = new \DOMDocument();
        $this->document->loadXML('<?xml version="1.0"?>');

        $body = $this->getBody();
        $id   = 1;

        foreach ($translations as $source => $translation) {
            $unit = $this->getUnit($id ++, $source, $translation);
            $body->appendChild($unit);
        }

        return $this->document->saveXML();
    }

    protected function getExtension()
    {
        return 'xml';
    }

    protected function getUnit($id, $source, $translation)
    {
        $unit = $this->document->createElement('trans-unit');
        $unit->setAttribute('id', $id ++);

        $unit->appendChild($this->document->createElement('source', $source));
        $unit->appendChild($this->document->createElement('target', $translation));

        return $unit;
    }

    protected function getBody($sourceLanguage = null, $sourceFile = null, $dataType = 'plaintext')
    {
        $body = $this->document->createElement('body');

        $file = $this->document->createElement('xliff');
        $file->setAttribute('datatype', $dataType);

        if ($sourceLanguage) {
            $file->setAttribute('source-language', $sourceLanguage);
        }

        if ($sourceFile) {
            $file->setAttribute('original', $sourceFile);
        }

        $file->appendChild($body);

        $xliff = $this->document->createElement('xliff');
        $xliff->setAttribute('version', '1.2');
        $xliff->setAttribute('xmlns', 'urn:oasis:names:tc:xliff:document:1.2');
        $xliff->appendChild($file);

        $this->document->appendChild($xliff);

        return $body;
    }
}