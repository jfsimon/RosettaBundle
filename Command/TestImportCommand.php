<?php

namespace BeSimple\RosettaBundle\Command;

use BeSimple\RosettaBundle\Command\AbstractCommand;
use BeSimple\RosettaBundle\Command\Formatter\CellFormatter;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Translation\MessageCatalogue;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class TestImportCommand extends AbstractCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setDefinition(array(
                new InputArgument('file',    InputArgument::REQUIRED,     'File to import', null),
                new InputOption('with', 'w', InputOption::VALUE_OPTIONAL, 'Loader adapter', null),
            ))
            ->setName('rosetta:test:import')
            ->setDescription('Imports a translation file and display the messages.')
        ;
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $catalogue = $this->load($input);
        $feedback  = $this->format($catalogue);

        $output->write($feedback);
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
    protected function format(MessageCatalogue $catalogue)
    {
        $headers = array(
            'Domain'      => CellFormatter::ALIGN_RIGHT,
            'Text'        => CellFormatter::ALIGN_LEFT,
            'Parameters'  => CellFormatter::ALIGN_LEFT,
            'Translation' => CellFormatter::ALIGN_LEFT,
        );

        $body    = array();
        $first   = true;
        $guesser = $this
            ->getContainer()
            ->get('be_simple_rosetta.factory')
            ->getParametersGuesser()
        ;

        foreach ($catalogue->all() as $domain => $messages) {
            $first ? $first = false : $body[] = '-';

            foreach ($messages as $text => $translation) {
                $parameters = $guesser->validate($translation, $guesser->guess($text));

                if (count($parameters) > 0) {
                    $text = $this
                        ->getFormatterHelper()
                        ->formatHighlight($text, $parameters ?: array(), 'fg=red')
                    ;

                    $translation = $this
                        ->getFormatterHelper()
                        ->formatHighlight($translation, $parameters ?: array(), 'fg=red')
                    ;
                }

                $body[] = array(
                    '<fg=blue>'.$domain.'</fg=blue>',
                    '<fg=green>'.$text.'</fg=green>',
                    count($parameters) > 0
                        ? '<fg=yellow><fg=blue>[</fg=blue>'.implode('<fg=blue>,</fg=blue> ', $parameters).'<fg=blue>]</fg=blue></fg=yellow>'
                        : '<fg=red>none</fg=red>',
                    '<fg=green>'.$translation.'</fg=green>',
                );
            }
        }

        return $this
            ->getFormatterHelper()
            ->formatTable($headers, $body)
        ;
    }
}
