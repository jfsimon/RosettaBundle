<?php

namespace BeSimple\RosettaBundle\Rosetta\Collector;

use BeSimple\RosettaBundle\Rosetta\Workflow\InputCollection;
use BeSimple\RosettaBundle\Rosetta\Workflow\Input;
use BeSimple\RosettaBundle\Rosetta\Factory;
use BeSimple\RosettaBundle\Rosetta\Locator;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
abstract class AbstractCollector
{
    /**
     * @var Factory
     */
    protected $factory;

    /**
     * @var Locator
     */
    protected $locator;

    /**
     * @var InputCollection
     */
    protected $inputs;

    /**
     * Constructor.
     *
     * @param Factory $factory A Factory instance
     * @param Locator $locator A Locator instance
     */
    public function __construct(Factory $factory, Locator $locator)
    {
        $this->factory = $factory;
        $this->locator = $locator;
        $this->inputs  = new InputCollection();
    }

    /**
     * Adds an input to the collector.
     *
     * @param Input $input An Input instance
     *
     * @return AbstractCollector This instance
     */
    public function add(Input $input)
    {
        $this->inputs->add($input);

        return $this;
    }

    /**
     * Fetches akk inputs from the collector.
     *
     * @return InputCollection An InputCollection instance
     */
    public function fetch()
    {
        $inputs = $this->inputs;
        $this->inputs = new InputCollection();

        return $inputs;
    }
}
