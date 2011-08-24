<?php

namespace BeSimple\RosettaBundle\Command\TableFormatter;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class Header extends AbstractRow implements RowInterface
{
    /**
     * {@inheritdoc}
     */
    public function getLength(Column $column)
    {
        return strlen($this->getLabel($column));
    }

    /**
     * {@inheritdoc}
     */
    public function render(Column $column)
    {
        return $this->renderValue($column, $this->getLabel($column), 'label');
    }

    /**
     * @param Column $column
     * @return string
     */
    protected function getLabel(Column $column)
    {
        return $column->getLabel() ?: ucfirst(str_replace(array('-', '_'), ' ', $column->getKey()));
    }
}
