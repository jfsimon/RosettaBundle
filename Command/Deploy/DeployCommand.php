<?php

namespace Bundle\RosettaBundle\Command\Deploy;

use Bundle\DeploymentBundle\Deployer\Deployer;
use Bundle\RosettaBundle\Command\ReportCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Bundle\RosettaBundle\Service\Scanner\Scanner;

abstract class DeployCommand extends ReportCommand
{
    protected function deploy(Deployer $deployer, InputInterface $input, OutputInterface $output)
    {
        $messages = $deployer->getMessages();

        $deployer->process();

        $this->report($messages, $input, $output);
    }
}
