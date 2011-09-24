<?php

namespace BeSimple\RosettaBundle\Command\Formatter;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class RowFormatter
{
    /**
     * @var array
     */
    private $cellFormatters;

    /**
     * @var string
     */
    private $indent;

    /**
     * @var string
     */
    private $gutter;

    /**
     * Constructor.
     *
     * @param array  $cellFormatters
     * @param string $indent
     * @param string $gutter
     */
    public function __construct(array $cellFormatters, $indent = '', $gutter = ' ')
    {
        $this->cellFormatters = $cellFormatters;
        $this->indent         = $indent;
        $this->gutter         = $gutter;
    }

    /**
     * Scans texts length for vertical alignment.
     *
     * @param array $texts
     *
     * @return RowFormatter
     */
    public function scanLengths(array $texts)
    {
        foreach ($this->cellFormatters as $index => $cellFormatter) {
            if (isset($texts[$index])) {
                $cellFormatter->scanLength($texts[$index]);
            }
        }

        return $this;
    }

    /**
     * Resets cells length.
     *
     * @return RowFormatter
     */
    public function resetLengths()
    {
        foreach ($this->cellFormatters as $cellFormatter) {
            $cellFormatter->setLength(0);
        }

        return $this;
    }

    /**
     * Format an array of texts.
     *
     * @param array $texts
     *
     * @return string
     */
    public function format(array $texts)
    {
        $cells = array();

        foreach ($this->cellFormatters as $index => $cellFormatter) {
            $text = isset($texts[$index]) ? $texts[$index] : '';
            $cells[] = $cellFormatter->format($text);
        }

        return $this->indent.implode($this->gutter, $cells);
    }

    /**
     * Fills row with repeated string.
     *
     * @param string $string
     *
     * @return string
     */
    public function fill($string)
    {
        $cells = array();

        foreach ($this->cellFormatters as $cellFormatter) {
            $cells[] = $cellFormatter->fill($string);
        }

        return $this->indent.implode($this->gutter, $cells);
    }

    /**
     * @param array $cellFormatters
     *
     * @return RowFormatter
     */
    public function setCellFormatters($cellFormatters)
    {
        $this->cellFormatters = $cellFormatters;

        return $this;
    }

    /**
     * @return array
     */
    public function getCellFormatters()
    {
        return $this->cellFormatters;
    }

    /**
     * @param string $gutter
     *
     * @return RowFormatter
     */
    public function setGutter($gutter)
    {
        $this->gutter = $gutter;

        return $this;
    }

    /**
     * @return string
     */
    public function getGutter()
    {
        return $this->gutter;
    }

    /**
     * @param string $indent
     *
     * @return RowFormatter
     */
    public function setIndent($indent)
    {
        $this->indent = $indent;

        return $this;
    }

    /**
     * @return string
     */
    public function getIndent()
    {
        return $this->indent;
    }
}
