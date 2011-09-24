<?php

namespace BeSimple\RosettaBundle\Rosetta\Task;

use BeSimple\RosettaBundle\Rosetta\Event\IgnoredMessageTaskEvent;
use BeSimple\RosettaBundle\Rosetta\Event\ProcessedMessageTaskEvent;
use BeSimple\RosettaBundle\Rosetta\Event\TaskEvents;
use BeSimple\RosettaBundle\Entity\Message;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
abstract class AbstractTask
{
    /**
     * @var EventDispatcher
     */
    private $dispatcher;

    /**
     * @param EventDispatcher $dispatcher
     */
    public function __construct(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @see TranslateInterface
     */
    public function process(array $messages)
    {
        $processedMessages = array();

        foreach ($messages as &$message) {
            $processedMessage = $this->processMessage($message);

            if (!is_null($processedMessage)) {
                $processedMessages[] = $processedMessage;
            }
        }

        return $this->finalizeProcess($processedMessages);
    }

    /**
     * Processes a single message.
     *
     * @param Message $message A Message instance
     * @return Message|null A Message instance or null
     */
    protected function processMessage(Message $message)
    {
        return $message;
    }

    /**
     * Finalizes process.
     *
     * @param array $messages An array of Message instances
     * @return array An array of Message instances
     */
    protected function finalizeProcess(array $messages)
    {
        return $messages;
    }

    /**
     * Dispatches a TaskEvents::onMessageProcessed event.
     *
     * @param Message $message A Message instance
     * @param array   $infos   A key/val hash of $infos
     */
    protected function dispatchMessageProcessed(Message $message, array $infos = array())
    {
        $this->dispatcher->dispatch(
            TaskEvents::onMessageProcessed,
            new ProcessedMessageTaskEvent($this, $message, $infos)
        );
    }

    /**
     * Dispatches a TaskEvents::onMessageProcessed event.
     *
     * @param Message $message A Message instance
     * @param string  $reason  A reason message
     */
    protected function dispatchMessageIgnored(Message $message, $reason)
    {
        $this->dispatcher->dispatch(
            TaskEvents::onMessageIgnored,
            new IgnoredMessageTaskEvent($this, $message, $reason)
        );
    }
}
