<?php

namespace BeSimple\RosettaBundle\Rosetta\Task;

use BeSimple\RosettaBundle\Entity\Message;
use BeSimple\RosettaBundle\Entity\Translation;
use BeSimple\RosettaBundle\Rosetta\Dumper;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class DumpTask extends AbstractTask implements TaskInterface
{
    /**
     * @var Dumper
     */
    protected $dumper;

    /**
     * Constructor.
     *
     * @param EventDispatcher $dispatcher
     * @param Dumper          $dumper
     */
    public function __construct(EventDispatcher $dispatcher, Dumper $dumper)
    {
        parent::__construct($dispatcher);

        $this->dumper = $dumper;
    }

    /**
     * {@inheritdoc}
     */
    public function processMessage(Message $message)
    {
        $this->dumper->add($message);
        $this->dispatchMessageProcessed($message, array());

        return $message;
    }

    /**
     * {@inheritdoc}
     */
    protected function finalizeProcess(array $messages)
    {
        $this->dumper->merge();

        return $messages;
    }

    /**
     * {@inheritdoc}
     */
    public function getAction()
    {
        return 'dump translated messages';
    }
}
