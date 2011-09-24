<?php

namespace BeSimple\RosettaBundle\Rosetta\Task;

use BeSimple\RosettaBundle\Entity\Message;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
interface TaskInterface
{
    /**
     * Processes given messages.
     *
     * @param Message[] $messages An array of Message instances
     *
     * @return Messages[]|null A Message instance or null
     */
    function process(array $messages);

    /**
     * Returns task action for console printing.
     *
     * @return string Task action
     */
    function getAction();
}
