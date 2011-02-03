<?php

namespace Bundle\RosettaBundle\Command\Import;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BundleImportCommand extends ImportCommand
{
    protected function configure()
    {
        parent::configure();

        $this
            ->setDefinition(array(
                new InputArgument('bundle', InputArgument::REQUIRED, 'Name of bundle to import'),
            ))
            ->setName('rosetta:import:bundle')
            ->setDescription('Scan given bundle files and import related translation files to Rosetta system')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $importer = $this->getService($input);
        $importer->importBundle($input->getArgument('bundle'));

        $this->processWorkflow($importer, $input, $output);
    }
}
