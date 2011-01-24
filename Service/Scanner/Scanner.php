<?php

namespace Bundle\RosettaBundle\Service\Scanner;

use Bundle\RosettaBundle\Service\Locator\Locator;
use Bundle\RosettaBundle\Service\Workflow\Input;

class Scanner
{
    protected $locator;
    protected $adapters;
    protected $tasks;

    public function __construct(Locator $locator, Workflow $workflow, array $config)
    {
        $this->locator = $locator;
        $this->workflow = $workflow;
        $this->adapters = $config['adapters'];
        unset($config['adapters']);
        $this->tasks = $config;
    }

    public function getAdapters()
    {
        return $this->adapters;
    }

	public function getTasks()
    {
        return $this->tasks;
    }

	public function setAdapters(array $adapters)
    {
        $this->adapters = $adapters;
    }

	public function setTasks(array $tasks)
    {
        $this->tasks = $tasks;
    }

	public function scanFile($file, $bundle=null, $workflow=true)
    {
        if(is_null($bundle)) {
            $bundle = $this->locator->guessBundle($file);
        }

        $extension = substr($file, strrpos($file, '.') + 1);

        if(! isset($this->adapter[$extension])) {
            return false;
        }

        $scanner = $this->getScanner();
        $scanner->loadFile($file);

        $messages = $scanner->getMessages();

        if($workflow) {
            $this->processWorkflow($messages, $bundle);
        }

        return $messages;
    }

    public function scanBundle($bundle, $workflow=true)
    {
        $path = $this->locator->locateBundle($bundle);
        $messages = array();

        foreach($this->locator->findFiles($path, array_keys($this->scanners)) as $file) {
            $messages = array_merge($messages, $this->scanFile($file, $bundle));
        }

        if($workflow) {
            $this->processWorkflow($messages, $bundle);
        }


        return $messages;
    }

    public function scanProject($workflow = true)
    {
        $messages = array();

        foreach($this->locator->getBundles() as $bundle) {
            $messages = array_merge($messages, $this->scanBundle($bundle));
        }

        if($workflow) {
            $this->processWorkflow($messages, $bundle);
        }

        return $messages;
    }

    protected function processWorkflow(array $messages, $bundle)
    {
        foreach($messages as $message) {
            $this->workflow->handle(new Input(
                $message['text'],
                $message['parameters'],
                $message['domain'],
                $bundle,
                $message['choice'],
                false
            ));
        }

        $this->workflow->pocess($this->tasks);
    }
}