<?php

namespace BeSimple\RosettaBundle\Command;

use BeSimple\RosettaBundle\Command\AbstractCommand;
use BeSimple\RosettaBundle\Command\Formatter\CellFormatter;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class TestScanCommand extends AbstractCommand
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
        $messages = $this->scan($input);
        $feedback = $this->format($messages);

        $output->write($feedback);
    }

    /**
     * @param InputInterface $input
     */
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
     * @param array $messages
     *
     * @return string
     */
    protected function format(array $messages)
    {
        $headers = array(
            'Domain'     => CellFormatter::ALIGN_RIGHT,
            'Text'       => CellFormatter::ALIGN_LEFT,
            'Parameters' => CellFormatter::ALIGN_LEFT,
        );

        $body = array();

        foreach ($messages as $message) {
            $text = $message['parameters']
                ? $this
                    ->getFormatterHelper()
                    ->formatHighlight($message['text'], $message['parameters'] ?: array(), 'fg=red')
                : $message['text'];

            $body[] = array(
                '<fg=blue>'.($message['domain'] ?: '--').'</fg=blue>',
                '<fg=green>'.$text.'</fg=green>',
                $message['parameters']
                    ? '<fg=yellow><fg=blue>[</fg=blue>'.implode('<fg=blue>,</fg=blue> ', $message['parameters']).'<fg=blue>]</fg=blue></fg=yellow>'
                    : '<fg=red>none</fg=red>',
            );
        }

        return $this
            ->getFormatterHelper()
            ->formatTable($headers, $body)
        ;
    }
}
