<?php

namespace BeSimple\RosettaBundle\Command\TableFormatter;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class TableColumn
{
    /**
     * @var string
     */
    protected $key;

    /**
     * @var string|null
     */
    protected $style;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var int
     */
    protected $length;

    /**
     * @param string $key
     * @param string|null $style
     * @param string|null $label
     */
    public function __construct($key, $style = null, $label = null)
    {
        $this->key    = $key;
        $this->style  = $style;
        $this->label  = $label;
        $this->length = 0;
    }

    /**
     * @param RowInterface $row
     *
     * @return TableColumn
     */
    public function scan(TableRowInterface $row)
    {
        $length = $row->getLength($this);

        if ($length > $this->length) {
            $this->length = $length;
        }

        return $this;
    }

    /**
     * @param $style
     *
     * @return TableColumn
     */
    public function setStyle($style)
    {
        $this->style = $style;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param string $label
     *
     * @return TableColumn
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }
}
