<?php

namespace BeSimple\RosettaBundle\Rosetta\Event;

use BeSimple\RosettaBundle\Entity\Message;
use BeSimple\RosettaBundle\Rosetta\Task\TaskInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
abstract class AbstractMessageTaskEvent extends Event
{
    /**
     * @var TaskInterface
     */
    private $task;

    /**
     * @var Message
     */
    private $message;

    /**
     * @param Message $message
     */
    public function __construct(TaskInterface $task, Message $message)
    {
        $this->task    = $task;
        $this->message = $message;
    }

    /**
     * @return TaskInterface
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * @return Message
     */
    public function getMessage()
    {
        return $this->message;
    }
}
