<?php

namespace BeSimple\RosettaBundle\Rosetta\Event;

use BeSimple\RosettaBundle\Entity\Message;
use BeSimple\RosettaBundle\Rosetta\Task\TaskInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class ProcessedMessageTaskEvent extends AbstractMessageTaskEvent
{
    /**
     * @var array
     */
    private $infos;

    /**
     * @param Message $message
     * @param array   $infos
     */
    public function __construct(TaskInterface $task, Message $message, array $infos = array())
    {
        parent::__construct($task, $message);
        $this->infos  = $infos;
    }

    /**
     * @return array
     */
    public function getInfos()
    {
        return $this->infos;
    }
}
