<?php

namespace BeSimple\RosettaBundle\Command\TableFormatter;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class Separator extends AbstractRow implements RowInterface
{
    /**
     * {@inheritdoc}
     */
    public function getLength(Column $column)
    {
        return 0;
    }

    /**
     * {@inheritdoc}
     */
    public function render(Column $column)
    {
        $times = ceil($column->getLength() / strlen($this->options['separator']));

        return $this->renderValue($column, substr(str_repeat($this->options['separator'], $times), 0, $column->getLength()), 'separator');
    }
}
