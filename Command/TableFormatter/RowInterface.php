<?php

namespace BeSimple\RosettaBundle\Command\TableFormatter;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
interface RowInterface
{
    /**
     * @return int
     */
    function getLength(Column $column);

    /**
     * @param Column $column
     * @param string $null
     * @return string
     */
    function render(Column $column);
}
