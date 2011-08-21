<?php

namespace BeSimple\RosettaBundle\Translation\Scanner;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
interface ScannerInterface
{
    /**
     * Scan a source code for messages.
     *
     * @param string $source  A source code to scan
     * @param array  $context An array of context variables
     *
     * @return array
     */
    function scan($source, array $context = array());
}
