<?php

namespace Bundle\RosettaBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\Ouput;
use Bundle\RosettaBundle\Service\Scanner\Scanner;

abstract class ReportCommand extends Command
{
    protected function report(array $items, InputInterface $input, OutputInterface $output)
    {
        $count = 0;
        $action = $this->getReportAction($input);

        foreach($items as $item) {
            $count ++;

            if($output->getVerbosity() > Output::VERBOSITY_NORMAL) {
                $message = '"'.$item.'" '.$action;
                $output->writeln($message, 'comment');
            }
        }

        if($output->getVerbosity() > Output::VERBOSITY_QUIET) {
            $message = ($count ? $count : 'no').' message'.($count > 1 ? 's' : '').' '.$action;
            $output->writeln($message, $count ? 'info' : 'error');
        }
    }

    abstract protected function getReportAction(InputInterface $input);
}
