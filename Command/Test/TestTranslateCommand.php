<?php

namespace BeSimple\RosettaBundle\Command\Test;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use BeSimple\RosettaBundle\Command\TableFormatter\Formatter;
use BeSimple\RosettaBundle\Command\TableFormatter\Column;
use BeSimple\RosettaBundle\Command\TableFormatter\Row;

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
                new InputOption('with', 'w', InputOption::VALUE_OPTIONAL, 'Translator adapter',   null),
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
        $from = $input->getOption('from')
            ?: $this->getContainer()->getParameter('be_simple_rosetta.locales.source');

        $to   = $input->getOption('to')
            ? explode(',', $input->getOption('to'))
            : $this->getContainer()->getParameter('be_simple_rosetta.locales.translations');

        return $this
            ->getContainer()
            ->get('be_simple_rosetta.factory')
            ->getTranslator($input->getOption('with'))
            ->translate($input->getArgument('string'), $to, $from)
        ;
    }

    /**
     * @param array $translations
     * @param OutputInterface $output
     */
    protected function output(array $translations, OutputInterface $output)
    {
        $formatter = Formatter::create($output)
            ->addColumn(new Column('locale', 'comment'))
            ->addColumn(new Column('translation', 'info'))
        ;

        foreach ($translations as $locale => $translation) {
            $formatter->addRow(new Row(array(
                'locale'      => $locale,
                'translation' => $translation,
            )));
        }

        $formatter->write();
    }
}