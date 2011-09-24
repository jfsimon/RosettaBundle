<?php

namespace BeSimple\RosettaBundle\Command\Collector;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class ScanCommand extends CollectorCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('rosetta:scan')
            ->setDescription('Scans source code for translation messages')
            ->setHeader('Welcome to Rosetta scanner', <<<EOF
This command scans your project files for translation messages.
When messages collected, you will be prompted for tasks to apply on them.
Turn off interaction (<comment>-n</comment> option) to automatically apply every tasks.
Turn verbose mode on (<comment>-v</comment> option) for additional feedback.
EOF
            )
        ;
    }

    protected function collect($bundle, $domain, array $options)
    {
        $scanner = $this
            ->getContainer()
            ->get('be_simple_rosetta.scanner')
            ->scanBundle($bundle, $domain)
        ;

        return $scanner->fetchInputs();
    }
}
