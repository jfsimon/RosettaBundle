<?php

namespace Bundle\RosettaBundle\Service\Workflow;

interface TaskInterface
{
    public function handle(array $messages);
    public function process(array $language);
}