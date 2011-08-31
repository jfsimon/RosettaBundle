<?php

namespace BeSimple\RosettaBundle\Command\TableFormatter;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class TableHeader extends AbstractTableRow implements TableRowInterface
{
    /**
     * {@inheritdoc}
     */
    public function getLength(TableColumn $column)
    {
        return strlen($this->getLabel($column));
    }

    /**
     * {@inheritdoc}
     */
    public function render(TableColumn $column)
    {
        return $this->renderValue($column, $this->getLabel($column), 'label');
    }

    /**
     * @param TableColumn $column
     *
     * @return string
     */
    protected function getLabel(TableColumn $column)
    {
        return $column->getLabel() ?: ucfirst(str_replace(array('-', '_'), ' ', $column->getKey()));
    }
}
