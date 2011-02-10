<?php

namespace Bundle\RosettaBundle\Command\Scan;

use Bundle\RosettaBundle\Command\WorkflowCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Bundle\RosettaBundle\Service\Scanner\Scanner;

abstract class ScanCommand extends WorkflowCommand
{
    protected function processWorkflow(Scanner $scanner, InputInterface $input, OutputInterface $output)
    {
        $messages = $scanner->getMessages();
        $scanner->process();

        $this->report($messages, $input, $output);
    }

    protected function getServiceName()
    {
        return 'rosetta.scanner';
    }

    protected function getBaseReportAction()
    {
        return 'scaned';
    }
}
