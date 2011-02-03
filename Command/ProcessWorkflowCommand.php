<?php

namespace Bundle\RosettaBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Bundle\RosettaBundle\Service\Scanner\Scanner;

abstract class ProcessWorkflowCommand extends Command
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

    protected function displayMessages(array $messages, InputInterface $input, OutputInterface $output)
    {
        $count = 0;

        foreach($messages as $message) {
            $count ++;

            if($output->getVerbosity() > Output::VERBOSITY_NORMAL) {
                $this->displayResult($message, $input, $output);
            }
        }

        if($output->getVerbosity() > Output::VERBOSITY_QUIET) {
            $this->displayResult($count, $input, $output);
        }
    }

    protected function displayMessage($text, InputInterface $input, OutputInterface $output)
    {
        $message = '"'.$text.'" '.$this->getDisplayAction($input);
        $output->writeln($message, 'comment');
    }

    protected function displayCount($count, InputInterface $input, OutputInterface $output)
    {
        $message = ($count ? $count : 'no').' message'.($count > 1 ? 's' : '').' '.$this->getDisplayAction($input);
        $output->writeln($message, $count ? 'info' : 'error');
    }

    protected function getDisplayAction(InputInterface $input)
    {
        if($input->hasParameterOption('fake')) {
            return 'found';
        }

        $actions = array($this->getDisplayBaseAction());

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

    abstract protected function getDisplayBaseAction();
    abstract protected function getServiceName();
}
