<?php

namespace Bundle\RosettaBundle\Command\Scan;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProjectScanCommand extends ScanCommand
{
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('rosetta:scan:project')
            ->setDescription('Scan project files and extract translations messages')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $scanner = $this->getService($input);
        $scanner->scanProject();

        $this->processWorkflow($scanner, $input, $output);
    }
}
