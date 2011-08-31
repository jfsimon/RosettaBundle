<?php

namespace BeSimple\RosettaBundle\Command\TableFormatter;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class TableSeparator extends AbstractTableRow implements TableRowInterface
{
    /**
     * {@inheritdoc}
     */
    public function getLength(TableColumn $column)
    {
        return 0;
    }

    /**
     * {@inheritdoc}
     */
    public function render(TableColumn $column)
    {
        $times = ceil($column->getLength() / strlen($this->options['separator']));

        return $this->renderValue($column, substr(str_repeat($this->options['separator'], $times), 0, $column->getLength()), 'separator');
    }
}
