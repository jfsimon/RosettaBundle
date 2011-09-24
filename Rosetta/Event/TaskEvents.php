<?php

namespace BeSimple\RosettaBundle\Rosetta\Event;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
final class TaskEvents
{
    const onMessageProcessed = 'be_simple_rosetta.task.message_processed';
    const onMessageIgnored   = 'be_simple_rosetta.task.message_ignored';
}
