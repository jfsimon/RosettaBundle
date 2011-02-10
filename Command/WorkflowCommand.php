<?php

namespace Bundle\RosettaBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Bundle\RosettaBundle\Service\Scanner\Scanner;

abstract class WorkflowCommand extends ReportCommand
{
    protected function configure()
    {
        $this
            ->addOption('fake',      'f', InputOption::VALUE_NONE, 'Fake : do nothing')
            ->addOption('translate', 't', InputOption::VALUE_NONE, 'Translate messages')
            ->addOption('choose',    'c', InputOption::VALUE_NONE, 'Choose best messages')
            ->addOption('deploy',    'd', InputOption::VALUE_NONE, 'Deploy messages')
        ;
    }

    protected function getService(InputInterface $input)
    {
        $service = $this->container->get($this->getServiceName());

        $service->setTasks(array(
            'translate' => $input->hasParameterOption('translate'),
            'choose'    => $input->hasParameterOption('choose'),
            'deploy'    => $input->hasParameterOption('deploy')
        ));

        return $service;
    }

    protected function getReportAction(InputInterface $input)
    {
        if($input->hasParameterOption('fake')) {
            return 'found';
        }

        $actions = array($this->getBaseReportAction());

        if($input->hasParameterOption('translate')) {
            $actions[] = 'translated';
        }

        if($input->hasParameterOption('choose')) {
            $action[] = 'choosen';
        }

        if($input->hasParameterOption('deploy')) {
            $action[] = 'deployed';
        }

        $last = ' and '.array_pop($actions);

        return implode(', ', $actions).$last;
    }

    abstract protected function getBaseReportAction();
    abstract protected function getServiceName();
}
