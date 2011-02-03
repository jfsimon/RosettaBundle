<?php

namespace Bundle\RosettaBundle\Service\Scanner;

use Bundle\RosettaBundle\Service\Locator\Locator;
use Bundle\RosettaBundle\Service\Workflow\Input;

class Scanner
{
    protected $locator;
    protected $adapters;
    protected $tasks;
    protected $messages;

    public function __construct(Locator $locator, Workflow $workflow, array $config)
    {
        $this->locator = $locator;
        $this->workflow = $workflow;
        $this->adapters = $config['adapters'];
        unset($config['adapters']);
        $this->tasks = $config;
        $this->messages = array();
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

	public function scanFile($file, $bundle=null)
    {
        if(is_null($bundle)) {
            $bundle = $this->locator->guessBundle($file);
        }

        $extension = substr($file, strrpos($file, '.') + 1);

        if(! isset($this->adapters[$extension])) {
            throw new \RuntimeException('Scanner adapter not found for file "'.$file.'"');
        }

        $adapter = $this->adapters[$extension];
        $adapter->scanFile($file, $bundle);

        $this->messages = array_merge($this->messages, $adapter->getMessages());
    }

    public function scanBundle($bundle)
    {
        $path = $this->locator->locateBundle($bundle);

        foreach($this->locator->findFiles($path, array_keys($this->scanners)) as $file) {
            $this->messages = array_merge($this->messages, $this->scanFile($file, $bundle));
        }
    }

    public function scanProject()
    {
        foreach($this->locator->getBundles() as $bundle) {
            $this->messages = array_merge($this->messages, $this->scanBundle($bundle));
        }
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function process()
    {
        foreach($this->messages as $message) {
            $this->workflow->handle(new Input(
                $message['text'],
                $message['parameters'],
                $message['domain'],
                $message['bundle'],
                $message['choice'],
                false
            ));
        }

        $this->workflow->pocess($this->tasks);
        $this->messages = array();
    }
}