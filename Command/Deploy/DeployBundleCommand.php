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
            ->setDefinition(array(
                new InputArgument('bundle', InputArgument::REQUIRED, 'Name of bundle to deploy'),
            ))
            ->setName('rosetta:deploy:bundle')
            ->setDescription('Deploy bundle translation files')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $deployer = $this->container->get('rosetta.deployer');
        $deployer->deployBundle($input->getArgument('bundle'));

        $this->deploy($deployer, $input, $output);
    }
}
