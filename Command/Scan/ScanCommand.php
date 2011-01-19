<?php

namespace Bundle\RosettaBundle\Command\Scan;

use Symfony\Bundle\FrameworkBundle\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

abstract class ScanCommand extends Command
{
    protected function configure()
    {
        $this
            ->addOption('store', 'd', InputOption::VALUE_NONE, 'Store messages in database')
            ->addOption('translate', 't', InputOption::VALUE_NONE, 'Translate messages')
        ;
    }

    protected function getRosettaService(InputInterface $input)
    {
        $rosetta = $this->container->get('rosetta');

        $rosetta->setOption('store', $input->hasParameterOption('store'));
        $rosetta->setOption('translate', $input->hasParameterOption('translate'));

        return $rosetta;
    }

    protected function displayMessages(array $messages, InputInterface $input, OutputInterface $output)
    {
        $count = 0;

        foreach($messages as $message) {
            $count ++;

            if($output->getVerbosity() > Output::VERBOSITY_NORMAL) {
                $this->displayResult($message->getText(), $input, $output);
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
        if($input->hasParameterOption('store')) {
            $action = 'stored';

            if($input->hasParameterOption('translate')) {
                $action .= ' and translated';
            }
        } else {
            $action = 'found';
        }
    }
}
