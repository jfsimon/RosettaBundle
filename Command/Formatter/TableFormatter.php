<?php

namespace BeSimple\RosettaBundle\Command\Formatter;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class TableFormatter
{
    /**
     * @var RowFormatter
     */
    private $rowFormatter;

    /**
     * @var array|null
     */
    private $header;

    /**
     * Constructor.
     *
     * @param RowFormatter $rowFormatter
     * @param array|null   $header
     */
    public function __construct(RowFormatter $rowFormatter, array $header = null)
    {
        $this->rowFormatter = $rowFormatter;
        $this->header       = $header;
    }

    /**
     * Formats an array of data.
     *
     * @param array $table
     *
     * @return string
     */
    public function format(array $table)
    {
        $this->rowFormatter->resetLengths();

        if ($this->header) {
            $this->rowFormatter->scanLengths($this->header);
        }

        foreach ($table as $row) {
            if (is_array($row)) {
                $this->rowFormatter->scanLengths($row);
            }
        }

        $rows = array();

        if ($this->header) {
            $rows[] = $this->rowFormatter->format($this->header);
            $rows[] = $this->rowFormatter->fill('-');
        }

        foreach ($table as $row) {
            if (is_array($row)) {
                $rows[] = $this->rowFormatter->format($row);
            } else if (is_string($row)) {
                $rows[] = $this->rowFormatter->fill($row);
            }
        }

        return implode("\n", $rows);
    }

    /**
     * @param array|null $header
     *
     * @return TableFormatter
     */
    public function setHeader($header)
    {
        $this->header = $header;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @param RowFormatter $rowFormatter
     *
     * @return TableFormatter
     */
    public function setRowFormatter($rowFormatter)
    {
        $this->rowFormatter = $rowFormatter;

        return $this;
    }

    /**
     * @return RowFormatter
     */
    public function getRowFormatter()
    {
        return $this->rowFormatter;
    }
}
