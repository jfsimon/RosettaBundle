<?php

namespace Bundle\RosettaBundle\Service\Live;

use BeSimple\RosettaBundle\Workflow\Workflow;

use BeSimple\RosettaBundle\Entity\ModelFactoryInterface;

use BeSimple\RosettaBundle\Task\TasksExecutor;

class Live extends TasksExecutor
{
    protected $locator;
    protected $workflow;
    protected $tasks;

    public function __construct(Locator $locator, Workflow $workflow, array $tasks)
    {
        $this->locator = $locator;
        $this->workflow = $workflow;
        $this->tasks = $tasks;
    }

    public function handle($text, array $parameters, $domain, $locale, $isChoice, array $backtrace)
    {
        $bundle = $this->guessBundleFromBacktrace($backtrace);

        $this->workflow->setTasks($this->tasks);
        $this->workflow->pushMessage($bundle, $domain, $text, $parameters, $isChoice);
        $this->workflow->run();
    }

    protected function guessBundleFromBacktrace(array $backtrace)
    {
        $caller = array_shift($backtrace);
        return $this->locator->guessBundleFromPath($caller['file']);
    }
}