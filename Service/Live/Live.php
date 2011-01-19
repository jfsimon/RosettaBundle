<?php

namespace Bundle\RosettaBundle\Service\Live;

use Bundle\RosettaBundle\Service\Workflow\Workflow;
use Bundle\RosettaBundle\Service\Workflow\Input;

class Live
{
    protected $locator;
    protected $workflow;
    protected $tasks;

    public function __construct(Locator $locator, Workflow $workflow, $config=array())
    {
        $this->locator = $locator;
        $this->workflow = $workflow;
        $this->tasks = $config['live'];
    }

    public function handle($text, array $parameters, $domain, $locale, $isChoice, array $backtrace)
    {
        $bundle = $this->guessBundleFromBacktrace($backtrace);
        $input = new Input($text, $parameters, $domain, $bundle, $isChoice, true);

        $this->workflow->handle($input, $this->tasks);
    }

    protected function guessBundleFromBacktrace(array $backtrace)
    {
        $caller = array_shift($backtrace);
        return $this->locator->guessBundleFromPath($caller['file']);
    }

}