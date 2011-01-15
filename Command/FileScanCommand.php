<?php

namespace Bundle\RosettaBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;

class FileScanCommand extends ScanCommand
{
    protected function configure()
    {
        parent::configure();

        $this
            ->setDefinition(array(
                new InputArgument('file', InputArgument::REQUIRED, 'The file to scan'),
            ))
            ->setName('rosetta:scan:file')
            ->setDescription('Scan given file and extract translations messages')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $rosetta = $this->getRosettaService($input);
        $messages = $this->container->get('rosetta')->scanFile($input->getArgument('file'));

        $this->displayMessages($messages, $input, $output);
    }
}
