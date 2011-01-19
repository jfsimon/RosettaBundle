<?php

namespace Bundle\RosettaBundle\Command\Scan;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;

class BundleScanCommand extends ScanCommand
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
        $rosetta = $this->getRosettaService($input);
        $messages = $this->container->get('rosetta')->scanProject();

        $this->displayMessages($messages, $input, $output);
    }
}
