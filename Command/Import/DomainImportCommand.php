<?php

namespace Bundle\RosettaBundle\Command\Import;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DomainImportCommand extends ImportCommand
{
    protected function configure()
    {
        parent::configure();

        $this
            ->setDefinition(array(
                new InputArgument('bundle', InputArgument::REQUIRED, 'Name of bundle containing domain'),
                new InputArgument('domain', InputArgument::REQUIRED, 'Name of domain to import'),
            ))
            ->setName('rosetta:import:domain')
            ->setDescription('Scan given bundle/domain and import related translation file to Rosetta system')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $importer = $this->getService($input);
        $importer->importDomainName($input->getArgument('bundle'), $input->getArgument('domain'));

        $this->processWorkflow($importer, $input, $output);
    }
}
