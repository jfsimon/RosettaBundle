<?php

namespace BeSimple\RosettaBundle\Command\TableFormatter;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class Row extends AbstractRow implements RowInterface
{
    /**
     * @var array
     */
    protected $values;

    /**
     * @param array $values
     */
    public function __construct(array $values, array $options = array())
    {
        parent::__construct($options);

        $this->values = $values;
        $this->value  = null;
    }

    /**
     * {@inheritdoc}
     */
    public function getLength(Column $column)
    {
        return strlen($this->getValue($column->getKey()));
    }

    /**
     * {@inheritdoc}
     */
    public function render(Column $column)
    {
        return $this->renderValue($column, $this->getValue($column->getKey()), $column->getStyle());
    }

    /**
     * @param Column $column
     * @return string
     */
    protected function getValue($key)
    {
        return isset($this->values[$key]) ? $this->values[$key] : null;
    }
}
