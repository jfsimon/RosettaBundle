<?php

namespace BeSimple\RosettaBundle\Rosetta\Event;

use BeSimple\RosettaBundle\Entity\Message;
use BeSimple\RosettaBundle\Rosetta\Task\TaskInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class IgnoredMessageTaskEvent extends AbstractMessageTaskEvent
{
    /**
     * @var string
     */
    private $reason;

    /**
     * @param Message $message
     */
    public function __construct(TaskInterface $task, Message $message, $reason)
    {
        parent::__construct($task, $message);
        $this->reason  = $reason;
    }

    /**
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }
}
