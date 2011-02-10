<?php

namespace Bundle\RosettaBundle\Command\Deploy;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

abstract class DeployDomainCommand extends DeployCommand
{
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('rosetta:deploy:project')
            ->setDescription('Deploy project translation files')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $deployer = $this->container->get('rosetta.deployer');
        $deployer->deployProject();

        $this->deploy($deployer, $input, $output);
    }
}
