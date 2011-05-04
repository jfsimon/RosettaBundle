<?php

namespace BeSimple\RosettaBundle\Workflow;

use BeSimple\RosettaBundle\Model\DomainCollection;

interface TasksInterface
{
    protected function run(DomainCollection $domains);
}