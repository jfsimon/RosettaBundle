<?php

namespace Bundle\RosettaBundle\Command\Import;

use Bundle\RosettaBundle\Command\ProcessWorkflowCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Bundle\RosettaBundle\Service\Importer\Importer;

abstract class ImportCommand extends ProcessWorkflowCommand
{
    protected function processWorkflow(Importer $importer, InputInterface $input, OutputInterface $output)
    {
        $messages = $importer->getMessages();
        $importer->process();

        $this->displayMessages($messages, $input, $output);
    }

    protected function getServiceName()
    {
        return 'rosetta.importer';
    }

    protected function getDisplayBaseAction()
    {
        return 'imported';
    }
}
