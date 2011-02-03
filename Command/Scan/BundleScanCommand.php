<?php

namespace Bundle\RosettaBundle\Command\Scan;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BundleScanCommand extends ScanCommand
{
    protected function configure()
    {
        parent::configure();

        $this
            ->setDefinition(array(
                new InputArgument('bundle', InputArgument::REQUIRED, 'Name of bundle to scan'),
            ))
            ->setName('rosetta:scan:bundle')
            ->setDescription('Scan given bundle files and extract translations messages')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $scanner = $this->getService($input);
        $scanner->scanBundle($input->getArgument('bundle'));

        $this->processWorkflow($scanner, $input, $output);
    }
}
