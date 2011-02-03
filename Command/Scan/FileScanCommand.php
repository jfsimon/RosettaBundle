<?php

namespace Bundle\RosettaBundle\Command\Scan;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FileScanCommand extends ScanCommand
{
    protected function configure()
    {
        parent::configure();

        $this
            ->setDefinition(array(
                new InputArgument('filename', InputArgument::REQUIRED, 'Name of file to scan'),
            ))
            ->setName('rosetta:scan:file')
            ->setDescription('Scan given file and extract translations messages')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $scanner = $this->getService($input);
        $scanner->scanFile($input->getArgument('filename'));

        $this->processWorkflow($scanner, $input, $output);
    }
}
