<?php

namespace Bundle\RosettaBundle\Command\Deploy;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

abstract class DeployDomainCommand extends DeployCommand
{
    protected function configure()
    {
        $this
            ->setDefinition(array(
                new InputArgument('bundle', InputArgument::REQUIRED, 'Name of bundle to deploy'),
                new InputArgument('domain', InputArgument::REQUIRED, 'Name of domain to deploy'),
            ))
            ->setName('rosetta:deploy:domain')
            ->setDescription('Deploy domain translation file')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $deployer = $this->container->get('rosetta.deployer');
        $deployer->deployDomainName($input->getArgument('bundle'), $input->getArgument('domain'));

        $this->deploy($deployer, $input, $output);
    }
}
