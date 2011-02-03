<?php

namespace Bundle\RosettaBundle\Command\Translate;

use Symfony\Bundle\FrameworkBundle\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Bundle\RosettaBundle\Service\Scanner\Scanner;

class TranslateStringCommand extends Command
{
    protected function configure()
    {
        $this
            ->setDefinition(array(
                new InputArgument('string',    InputArgument::REQUIRED, 'The string to translate'),
                new InputArgument('to-locale', InputArgument::REQUIRED, 'Output translation locale'),
            ))
            ->addOption('from-locale', 'f', InputOption::VALUE_REQUIRED, 'Input string locale')
            ->addOption('translator',  't', InputOption::VALUE_REQUIRED, 'Translator adapter class')
            ->setName('rosetta:translate:string')
            ->setDescription('Translate given string to given locale')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = $this->container->getParameter('rosetta.translator.config');

        $class = $input->hasParameterOption('translator') ? $input->getOption('translator') : $config['adapter'];
        $from = $input->hasParameterOption('from-locale') ? $input->getOption('from-locale') : $config['locale'];

        unset($config['adapter'], $config['locale']);

        $translator = new $class($config);
        $translation = $translator->translate($input->getArgument('string'), $from, $input->getArgument('to-locale'));

        $output->writeln($translation, 'info');
    }
}
