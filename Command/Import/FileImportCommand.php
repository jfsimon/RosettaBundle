<?php

namespace Bundle\RosettaBundle\Command\Import;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FileImportCommand extends ImportCommand
{
    protected function configure()
    {
        parent::configure();

        $this
            ->setDefinition(array(
                new InputArgument('filename', InputArgument::REQUIRED, 'Name of file to import'),
            ))
            ->setName('rosetta:import:file')
            ->setDescription('Import given translation file to Rosetta system')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $importer = $this->getService($input);
        $importer->importFile($input->getArgument('filename'));

        $this->processWorkflow($importer, $input, $output);
    }
}
