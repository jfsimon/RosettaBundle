<?php

namespace BeSimple\RosettaBundle\Workflow;

class TasksStack implements \IteratorAggregate, \Countable
{
    private $tasks;

    public function __construct()
    {
        $this->tasks = array();
    }

    public function has($name)
    {
        return isset($this->tasks[$name]);
    }

    public function get()
    {
        return $this->tasks;
    }

    public function set($tasks)
    {
        $this->clear();

        foreach ($tasks as $task) {
            $this->add($task);
        }
    }

    public function add($name, TaskInterface $task)
    {
        $this->tasks[$name] = $task;
    }

    public function remove($name)
    {
        unset($this->tasks[$name]);
    }

    public function count()
    {
        return count($this->tasks);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->tasks);
    }
}