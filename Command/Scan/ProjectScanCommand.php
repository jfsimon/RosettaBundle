<?php

namespace Bundle\RosettaBundle\Command\Scan;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;

class ProjectScanCommand extends ScanCommand
{
    protected function configure()
    {
        parent::configure();

        $this
            ->setDefinition(array(
                new InputArgument('bundle', InputArgument::REQUIRED, 'The bundle to scan'),
            ))
            ->setName('rosetta:scan:bundle')
            ->setDescription('Scan given bundle files and extract translations messages')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $rosetta = $this->getRosettaService($input);
        $messages = $this->container->get('rosetta')->scanBundle($input->getArgument('bundle'));

        $this->displayMessages($messages, $input, $output);
    }
}
