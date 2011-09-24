<?php

namespace BeSimple\RosettaBundle\Command\Collector;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class ImportCommand extends CollectorCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->addOption('locale', 'l', InputOption::VALUE_OPTIONAL, 'Restrict import to given locale')
            ->addOption('then',   't', InputOption::VALUE_OPTIONAL, 'Action on file after import')
            ->setName('rosetta:import')
            ->setDescription('Imports messages from translation files')
            ->setHeader('Welcome to Rosetta importer', <<<EOF
This command imports translation files into Rosetta database.
When messages imported, you will be prompted for tasks to apply on them.
Turn off interaction (<comment>-n</comment> option) to automatically apply every tasks.
Turn verbose mode on (<comment>-v</comment> option) for additional feedback.
EOF
            )
        ;
    }

    protected function collect($bundle, $domain, array $options)
    {
        $importer = $this
            ->getContainer()
            ->get('be_simple_rosetta.importer')
            ->importBundle($bundle, $domain, $options['locale'])
        ;

        if ($options['then'] === 'backup') {
            $backup = $this
                ->getContainer()
                ->get('be_simple_rosetta.backup')
            ;
        }

        if ($options['then'] !== 'none') {
            foreach ($importer->fetchFiles() as $file) {
                if ($options['then'] === 'backup') {
                    $backup->create($file);
                }

                if ($options['then'] === 'delete' || $options['then'] === 'backup') {
                    unlink($file);
                }
            }
        }

        return $importer->fetchInputs();
    }
}
