<?php

namespace BeSimple\RosettaBundle\Translation\Dumper;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
interface DumperInterface
{
    /**
     * Dumps translation messages into given file.
     *
     * @param string $resource A file path
     * @param array  $messages An array of translated messages
     */
    function dump($resource, array $messages);
}
