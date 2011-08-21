<?php

namespace BeSimple\RosettaBundle\Translation\Scanner\Exception;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class ScannerException extends \RuntimeException
{
    /**
     * Constructor.
     *
     * @param string $message
     * @param int    $line
     */
    public function __construct($message, $line)
    {
        $this->message = $message;
        $this->line    = $line;
    }

    /**
     * @param string $file A file path
     */
    public function setFile($file)
    {
        $this->file = $file;
    }
}
