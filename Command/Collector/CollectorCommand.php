<?php

namespace BeSimple\RosettaBundle\Command\Collector;

use BeSimple\RosettaBundle\Command\AbstractCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use BeSimple\RosettaBundle\Rosetta\Workflow\Tasks;
use BeSimple\RosettaBundle\Rosetta\Workflow\InputCollection;
use BeSimple\RosettaBundle\Rosetta\Task\TaskInterface;
use BeSimple\RosettaBundle\Rosetta\Event\TaskEvents;
use BeSimple\RosettaBundle\Rosetta\Event\AbstractMessageTaskEvent;
use BeSimple\RosettaBundle\Rosetta\Event\ProcessedMessageTaskEvent;
use BeSimple\RosettaBundle\Rosetta\Event\IgnoredMessageTaskEvent;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
abstract class CollectorCommand extends AbstractCommand
{
    public function filter(TaskInterface $task, InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('no-interaction')) {
            return true;
        }

        $question = 'Do you want to '.$task->getAction();
        $dialog   = $this->getDialogHelper();
        $process  = $dialog->askConfirmation($output, $question, true);

        if ($process) {
            $this->tasks[] = $task;
        }

        $output->writeln('<comment>Stating to '.$task->getAction().'</comment>');

        $this->processedMessages = 0;
        $this->ignoredMessages   = 0;

        return $process;
    }

    public function displayProcessedMessage(OutputInterface $output, ProcessedMessageTaskEvent $event, $verbose = false)
    {
        $this->processedMessages += 1;

        if ($verbose) {
            $output->writeln('<fg=blue>Message: </fg=blue>'.$event->getMessage()->getText());

            $formatter = TableFormatter::create($output, array('header' => false))
                ->addColumn(new TableColumn('key', 'comment'))
                ->addColumn(new TableColumn('value', 'info'))
            ;

            foreach ($event->getInfos() as $key => $value) {
                $formatter->addRow(new TableRow(array(
                    'key'   => $key,
                    'value' => $value
                )));
            }

            $formatter->write();
        }
    }

    public function displayIgnoredMessage(OutputInterface $output, IgnoredMessageTaskEvent $event, $verbose = false)
    {
        $this->ignoredMessages += 1;

        if ($verbose) {
            $output->writeln('<fg=blue>Message:</fg=blue> '.$event->getMessage()->getText());
            $output->writeln('  <fg=red>Ignored:</fg=red> <comment>'.$event->getReason().'</comment>');
        }
    }

    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setDefinition(array(
                new InputArgument('bundle', InputArgument::OPTIONAL, 'Bundle name', null),
                new InputArgument('domain', InputArgument::OPTIONAL, 'Domain name', null),
            ))
        ;
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->displayHeader($output);

        $dispatcher = $this
            ->getContainer()
            ->get('event_dispatcher')
        ;

        $verbose = $input->getOption('verbose');
        $that    = $this;

        $dispatcher->addListener(TaskEvents::onMessageProcessed, function(ProcessedMessageTaskEvent $event) use ($that, $output, $verbose) {
            $that->displayProcessedMessage($output, $event, $verbose);
        });

        $dispatcher->addListener(TaskEvents::onMessageIgnored, function(IgnoredMessageTaskEvent $event) use ($that, $output, $verbose) {
            $that->displayIgnoredMessage($output, $event, $verbose);
        });

        $bundles = $input->getArgument('bundle')
            ? array($input->getArgument('bundle'))
            : $this
                ->getContainer()
                ->get('be_simple_rosetta.locator')
                ->getProcessedBundles()
            ;

        $inputs = new InputCollection();
        foreach ($bundles as $bundle) {
            $inputs->merge($this->collect(
                $bundle,
                $input->getArgument('domain'),
                $input->getOptions()
            ));
        }

        $that   = $this;
        $filter = function(TaskInterface $task) use ($that, $input, $output) {
            return $that->filter($task, $input, $output);
        };

        $count = $this
            ->getContainer()
            ->get('be_simple_rosetta.processor')
            ->setTaskFilter($filter)
            ->process($inputs, $this->getTasks())
        ;

        $this->displaySummary($output, $count);
    }

    abstract protected function collect($bundle, $domain, array $options);

    protected function getTasks()
    {
        return $this
            ->getContainer()
            ->get('be_simple_rosetta.tasks')
            ->configure(Tasks::DEFAULTS)
        ;
    }

    protected function displaySummary(OutputInterface $output, $count)
    {
        $message = $this
            ->getFormatterHelper()
            ->formatSummary(
                $count > 0 ? $count.' messages successfully processed' : 'No message processed',
                $count > 0
            )
        ;

        $output->write($message);
    }
}
