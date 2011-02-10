<?php

namespace Bundle\RosettaBundle\Command\Import;

use Bundle\RosettaBundle\Command\WorkflowCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Bundle\RosettaBundle\Service\Importer\Importer;

abstract class ImportCommand extends WorkflowCommand
{
    protected function processWorkflow(Importer $importer, InputInterface $input, OutputInterface $output)
    {
        $messages = $importer->getMessages();
        $importer->process();

        $this->report($messages, $input, $output);
    }

    protected function getServiceName()
    {
        return 'rosetta.importer';
    }

    protected function getBaseReportAction()
    {
        return 'imported';
    }
}
