<?php

namespace Bundle\RosettaBundle\Service\Importer;

use Bundle\RosettaBundle\Service\Workflow\Workflow;
use Bundle\RosettaBundle\Service\Locator\Locator;

class Importer
{
    public function __construct(Locator $locator, Workflow $workflow, array $config)
    {
        $this->locator = $locator;
        $this->workflow = $workflow;

        $this->config = $config;
    }
}