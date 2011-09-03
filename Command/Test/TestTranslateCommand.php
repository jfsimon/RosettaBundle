<?php

namespace BeSimple\RosettaBundle\Command\Test;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use BeSimple\RosettaBundle\Command\TableFormatter\TableFormatter;
use BeSimple\RosettaBundle\Command\TableFormatter\TableColumn;
use BeSimple\RosettaBundle\Command\TableFormatter\TableRow;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class TestTranslateCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setDefinition(array(
                new InputArgument('string',  InputArgument::REQUIRED,     'String to translate',  null),
                new InputOption('from', 'f', InputOption::VALUE_OPTIONAL, 'Source string locale', null),
                new InputOption('to',   't', InputOption::VALUE_OPTIONAL, 'Translation locales',  null),
                new InputOption('with', 'w', InputOption::VALUE_OPTIONAL, 'Translator adapters',   null),
            ))
            ->setName('rosetta:test:translate')
            ->setDescription('Translates a string and display the result.')
        ;
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output($this->translate($input), $output);
    }

    protected function translate(InputInterface $input)
    {
        $factory = $this
            ->getContainer()
            ->get('be_simple_rosetta.factory')
        ;

        $from = $input->getOption('from')
            ?: $this->getContainer()->getParameter('be_simple_rosetta.locales.source');

        $to = $input->getOption('to')
            ? explode(',', $input->getOption('to'))
            : $this->getContainer()->getParameter('be_simple_rosetta.locales.translations');

        $with = $input->getOption('with')
            ? explode(',', $input->getOption('with'))
            : $factory->getTranslatorAliases();

        $stack = array();

        foreach ($with as $adapter) {
            $stack[$adapter] = $factory
                ->getTranslator($adapter)
                ->translate($input->getArgument('string'), $from, $to)
            ;
        }

        return $stack;
    }

    /**
     * @param array $stack
     * @param OutputInterface $output
     */
    protected function output(array $stack, OutputInterface $output)
    {
        $formatter = TableFormatter::create($output)
            ->addColumn(new TableColumn('locale', 'info'))
            ->addColumn(new TableColumn('service', 'comment'))
            ->addColumn(new TableColumn('translation', 'info'))
        ;

        foreach ($stack as $adapter => $translations) {
            foreach ($translations->allLocales() as $locale) {
                $formatter->addRow(new TableRow(array(
                    'service'     => $adapter,
                    'locale'      => $locale,
                    'translation' => $translations->get($locale) ?: '<error>'.$translations->getError($locale).'</error>',
                )));
            }
        }

        $formatter->write();
    }
}
