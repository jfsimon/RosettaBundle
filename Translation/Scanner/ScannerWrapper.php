<?php

namespace BeSimple\RosettaBundle\Translation\Scanner;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class ScannerWrapper implements ScannerInterface
{
    /**
     * @var array
     */
    protected $scanners;

    /**
     * Constructor.
     *
     * @param array $scanners An array of scanners.
     */
    public function __construct(array $scanners)
    {
        $this->clear();

        foreach ($scanners as $scanner) {
            $this->add($scanner);
        }
    }

    /**
     * Removes wrapped scanners.
     *
     * @return ScannerWrapper This ScannerWrapper instance
     */
    public function clear()
    {
        $this->scanners = array();

        return $this;
    }

    /**
     * Adds a scanner.
     *
     * @param ScannerInterface $scanner A scanner to wrap
     *
     * @return ScannerWrapper This ScannerWrapper instance
     */
    public function add(ScannerInterface $scanner)
    {
        $this->scanners[] = $scanner;

        return $this;
    }

    /**
     * Returns wrapped scanners.
     *
     * @return array An array of scanners
     */
    public function all()
    {
        return $this->scanners;
    }

    /**
     * {@inheritdoc}
     */
    public function scan($resource, array $context = array())
    {
        $messages = array();
        $content  = realpath($resource) ? file_get_contents($resource) : $resource;

        foreach ($this->scanners as $scanner) {
            $messages = array_merge($messages, $scanner->scan($content, $context));
        }

        return $messages;
    }
}
