<?php

namespace BeSimple\RosettaBundle\Command\Test;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Translation\MessageCatalogue;
use BeSimple\RosettaBundle\Command\TableFormatter\TableFormatter;
use BeSimple\RosettaBundle\Command\TableFormatter\TableColumn;
use BeSimple\RosettaBundle\Command\TableFormatter\TableRow;
use BeSimple\RosettaBundle\Command\TableFormatter\TableSeparator;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class TestLoadCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setDefinition(array(
                new InputArgument('file',    InputArgument::REQUIRED,     'File to load',    null),
                new InputOption('with', 'w', InputOption::VALUE_OPTIONAL, 'Loader adapter', null),
            ))
            ->setName('rosetta:test:load')
            ->setDescription('Loads a translation file and display the messages.')
        ;
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output($this->load($input), $output);
    }

    protected function load(InputInterface $input)
    {
        $file = $input->getArgument('file');
        $with = $input->getOption('with') ?: substr($file, strrpos($file, '.') + 1);

        if (substr($file, 0, 1) !== DIRECTORY_SEPARATOR) {
            $root = $this->getContainer()->getParameter('kernel.root_dir').DIRECTORY_SEPARATOR.'..';
            $file = realpath($root.DIRECTORY_SEPARATOR.$file);
        }

        return $this
            ->getContainer()
            ->get('be_simple_rosetta.factory')
            ->getLoader($with)
            ->load($file, '-')
        ;
    }

    /**
     * @param array $translations
     * @param OutputInterface $output
     */
    protected function output(MessageCatalogue $catalogue, OutputInterface $output)
    {
        $output->getFormatter()->setStyle('parameter', new OutputFormatterStyle('red', null, array('bold')));

        $formatter = TableFormatter::create($output)
            ->addColumn(new TableColumn('domain', 'comment'))
            ->addColumn(new TableColumn('text', 'info'))
            ->addColumn(new TableColumn('parameters', 'comment'))
            ->addColumn(new TableColumn('translation', 'info'))
        ;

        $guesser = $this
            ->getContainer()
            ->get('be_simple_rosetta.factory')
            ->getParametersGuesser()
        ;

        $first = true;

        foreach ($catalogue->all() as $domain => $messages) {
            $first ? $first = false : $formatter->addRow(new TableSeparator());

            foreach ($messages as $text => $translation) {
                $row = new TableRow(array(
                    'domain'      => $domain,
                    'text'        => $text,
                    'parameters'  => '--',
                    'translation' => $translation,
                ));

                foreach ($guesser->validate($translation, $guesser->guess($text)) as $parameter) {
                    $row->highlight($parameter, 'parameter');
                }

                $formatter->addRow($row);
            }
        }

        $formatter->write();
    }
}
