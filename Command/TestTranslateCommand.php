<?php

namespace BeSimple\RosettaBundle\Command;

use BeSimple\RosettaBundle\Command\AbstractCommand;
use BeSimple\RosettaBundle\Command\Formatter\CellFormatter;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class TestTranslateCommand extends AbstractCommand
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
        $translations = $this->translate($input);
        $feedback     = $this->format($translations);

        $output->write($feedback);
    }

    /**
     * @param InputInterface $input
     *
     * @return array
     */
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

        $translations = array();

        foreach ($with as $adapter) {
            $translations[$adapter] = $factory
                ->getTranslator($adapter)
                ->translate($input->getArgument('string'), $from, $to)
            ;
        }

        return $translations;
    }

    /**
     * @param array $stack
     *
     * @return string
     */
    protected function format(array $translations)
    {
        $headers = array(
            'Service'     => CellFormatter::ALIGN_RIGHT,
            'Locale'      => CellFormatter::ALIGN_LEFT,
            'Translation' => CellFormatter::ALIGN_LEFT,
        );

        $body = array();

        foreach ($translations as $adapter => $at) {
            foreach ($at->allLocales() as $locale) {
                $translation = $translations->get($locale);

                $body[] = array(
                    '<fg=blue>'.$adapter.'</fg=blue>',
                    '<fg=yellow>'.$locale.'</fg=yellow>',
                    $translation
                        ? '<fg=green>'.$translation.'</fg=green>'
                        : '<fg=red>'.$translations->getError($locale).'</fg=red>',
                );
            }
        }

        return $this
            ->getFormatterHelper()
            ->formatTable($headers, $body)
        ;
    }
}
