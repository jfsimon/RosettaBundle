<?php

namespace BeSimple\RosettaBundle\Command\TableFormatter;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
interface TableRowInterface
{
    /**
     * @return int
     */
    function getLength(TableColumn $column);

    /**
     * @param Column $column
     * @param string $null
     * @return string
     */
    function render(TableColumn $column);
}
