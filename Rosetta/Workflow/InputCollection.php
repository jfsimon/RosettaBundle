<?php

namespace BeSimple\RosettaBundle\Rosetta\Workflow;

use BeSimple\RosettaBundle\Entity\HelperInterface;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class InputCollection implements \Countable
{
    /**
     * @var Input[]
     */
    protected $inputs;

    /**
     * Constructor.
     *
     * @param array $inputs
     */
    public function __construct(array $inputs = array())
    {
        $this->inputs = array();

        foreach ($inputs as $input) {
            $this->add($input);
        }
    }

    /**
     * Adds an Input.
     *
     * @param Input $input
     *
     * @return Inputs This instance
     */
    public function add(Input $input)
    {
        $key = $input->getIdentifier();

        if (isset($this->inputs[$key])) {
            $this
                ->inputs[$key]
                ->mergeParameters($input->getParameters())
                ->mergeTranslations($input->getTranslations())
            ;
        } else {
            $this->inputs[$key] = $input;
        }

        return $this;
    }

    /**
     * Merge an inputs stack.
     *
     * @param Inputs $inputs An Inputs instance
     *
     * @return Inputs This instance
     */
    public function merge(Inputs $inputs)
    {
        foreach ($inputs->all() as $input) {
            $this->add($input);
        }

        return $this;
    }

    /**
     * Returns an array of all inputs.
     *
     * @return Input[] An array of inputs
     */
    public function all()
    {
        return $this->inputs;
    }

    /**
     * Extracts inputs from the top of the stack.
     *
     * @param integer $length Count of inputs to extract
     *
     * @return Inputs This instance
     */
    public function extract($length)
    {
        return new InputCollection(array_splice($this->inputs, 0, $length));
    }

    /**
     * Returns inputs count.
     *
     * @return int
     */
    public function count()
    {
        return count($this->inputs);
    }

    /**
     * Tests inputs validity.
     *
     * @return bool
     */
    public function isValid()
    {
        foreach ($this->inputs as $input) {
            if (!$input->isValid()) {
                return false;
            }
        }

        return true;
    }
}
