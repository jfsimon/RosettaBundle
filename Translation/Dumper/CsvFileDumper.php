<?php

namespace BeSimple\RosettaBundle\Translation\Dumper;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class CsvFileDumper extends AbstractFileDumper implements DumperInterface
{
    /**
     * @var string
     */
    private $delimiter = ';';

    /**
     * @var string
     */
    private $enclosure = '"';

    /**
     * @var string
     */
    private $escape    = '\\';

    /**
     * {@inheritdoc}
     */
    public function dump($resource, array $messages)
    {
        $content = '';

        foreach ($messages as $id => $trans) {
            $content .= $this->dumpString($id).$this->delimiter.$this->dumpString($id)."\n";
        }

        $this->write($resource, $content);
    }

    /**
     * CSV controls setter.
     *
     * @param string $delimiter Delimiter character
     * @param string $enclosure Enclosure character
     * @param string $escape    Escape character
     */
    public function setCsvControl($delimiter = ';', $enclosure = '"', $escape = '\\')
    {
        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
        $this->escape    = $escape;
    }

    /**
     * Returns a formatted string.
     *
     * @param string $string A string to format
     *
     * @return string The formatted string
     */
    private function dumpString($string)
    {
        return $this->enclosure.str_replace($this->enclosure, $this->escape.$this->enclosure, (string) $string).$this->enclosure;
    }
}
