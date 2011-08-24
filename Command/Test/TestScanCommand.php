<?php

namespace BeSimple\RosettaBundle\Command\Test;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use BeSimple\RosettaBundle\Command\TableFormatter\Formatter;
use BeSimple\RosettaBundle\Command\TableFormatter\Column;
use BeSimple\RosettaBundle\Command\TableFormatter\Row;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class TestScanCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setDefinition(array(
                new InputArgument('file',    InputArgument::REQUIRED,     'File to scan',    null),
                new InputOption('with', 'w', InputOption::VALUE_OPTIONAL, 'Scanner adapter', null),
            ))
            ->setName('rosetta:test:scan')
            ->setDescription('Scans a file and display the result.')
        ;
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output($this->scan($input), $output);
    }

    protected function scan(InputInterface $input)
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
            ->getScanner($with)
            ->scan($file)
        ;
    }

    /**
     * @param array $translations
     * @param OutputInterface $output
     */
    protected function output(array $translations, OutputInterface $output)
    {
        $output->getFormatter()->setStyle('parameter', new OutputFormatterStyle('red', null, array('bold')));

        $formatter = Formatter::create($output)
            ->addColumn(new Column('domain', 'comment'))
            ->addColumn(new Column('text', 'info'))
            ->addColumn(new Column('parameters', 'comment'))
        ;

        foreach ($translations as $message) {
            $row = new Row(array(
                'domain'     => $message['domain'] ?: '--',
                'text'       => $message['text'],
                'parameters' => $message['parameters'] ? '['.implode(', ', $message['parameters']).']' : '--',
            ));

            foreach ($message['parameters'] ?: array() as $parameter) {
                $row->highlight($parameter, 'parameter', array('text'));
            }

            $formatter->addRow($row);
        }

        $formatter->write();
    }
}
