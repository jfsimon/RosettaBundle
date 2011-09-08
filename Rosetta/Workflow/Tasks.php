<?php

namespace BeSimple\RosettaBundle\Rosetta\Workflow;

use BeSimple\RosettaBundle\Rosetta\Task\TaskInterface;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class Tasks
{
    const DEFAULTS  = 'defaults';
    const COLLECTOR = 'collector';
    const IMPORTER  = 'importer';
    const SCANNER   = 'scanner';

    /**
     * @var array
     */
    private $configs;

    /**
     * @var TaskInterface[]
     */
    private $tasks;

    /**
     * @var string[]
     */
    private $names;

    /**
     * Constructor.
     *
     * @param array $configs Tasks configuration
     */
    public function __construct(array $configs)
    {
        $this->configs = $configs;
        $this->tasks   = array();
        $this->names   = array();

        $this->configure(self::DEFAULTS);
    }

    /**
     * Returns an array of all tasks.
     *
     * @return array An array of tasks
     */
    public function all()
    {
        return $this->tasks;
    }

    /**
     * Adds a task to the stack.
     *
     * @param string        $name Task name
     * @param TaskInterface $task Task instance
     *
     * @return Tasks This instance
     */
    public function add($name, TaskInterface $task)
    {
        $this->tasks[(string) $name] = $task;

        return $this;
    }

    /**
     * Activate given tasks.
     *
     * @param string[] $names An array of task name
     *
     * @return Tasks This instance
     */
    public function activate(array $names)
    {
        $this->names = array();

        foreach ($names as $name) {
            $name = (string) $name;

            if (isset($this->tasks[$name])) {
                $this->names[] = $name;
            }
        }

        return $this;
    }

    /**
     * Activates all tasks.
     *
     * @return Tasks This instance
     */
    public function activateAll()
    {
        return $this->activate(array_keys($this->tasks));
    }

    /**
     * Returns an array of active tasks.
     *
     * @return TaskInterface[] An array of tasks
     */
    public function actives()
    {
        $tasks = array();

        foreach ($this->names as $name) {
            $tasks[$name] = $this->tasks[$name];
        }

        return $tasks;
    }

    /**
     * @param string $config
     * @return Tasks This instance
     */
    public function configure($config)
    {
        $this->activate($this->configs[$config]);

        return $this;
    }

    /**
     * Returns a new Tasks instance with given configuration.
     *
     * @param string $config A configuration template name
     *
     * @return Tasks This instance
     */
    public function reconfigure($config)
    {
        $tasks = clone $this;

        return $tasks->configure($config);
    }
}
