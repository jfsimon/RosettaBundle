<?php

namespace Bundle\RosettaBundle\Command\Import;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProjectImportCommand extends ImportCommand
{
    protected function configure()
    {
        parent::configure();

        $this
            ->setDefinition(array(
                new InputArgument('bundle', InputArgument::REQUIRED, 'The bundle to import'),
            ))
            ->setName('rosetta:import:project')
            ->setDescription('Import project translations files to Rosetta system')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $importer = $this->getService($input);
        $importer->importProject();

        $this->processWorkflow($importer, $input, $output);
    }
}
