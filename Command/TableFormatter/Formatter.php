<?php

namespace BeSimple\RosettaBundle\Command\TableFormatter;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class Formatter
{
    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var array
     */
    protected $columns;

    /**
     * @var array
     */
    protected $rows;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var array
     */
    static public $defaults = array(
        'gutter'    => '  ',
        'indent'    => '  ',
        'header'    => true,
        'null'      => '--',
        'separator' => '-',
    );

    /**
     * @param OutputInterface $output
     * @param array $options
     * @param array $columns
     */
    public function __construct(OutputInterface $output, array $options = array())
    {
        $this->output  = $output;
        $this->columns = array();
        $this->rows    = array();
        $this->options = array_merge(static::$defaults, $options);

        $this->setupOutputFormatter($output->getFormatter());
    }

    /**
     * @static
     * @param OutputInterface $output
     * @param array $options
     * @return Formatter
     */
    static public function create(OutputInterface $output, array $options = array())
    {
        return new static($output, $options);
    }

    /**
     * @param Column $column
     * @return Formatter
     */
    public function addColumn(Column $column)
    {
        $this->columns[] = $column;

        return $this;
    }

    /**
     * @param RowInterface $row
     * @return Formatter
     */
    public function addRow(RowInterface $row)
    {
        $row->setOptions($this->options);
        $this->rows[] = $row;

        return $this;
    }

    /**
     * @return string
     */
    public function render(array $rows = null)
    {
        $rows = $rows ?: $this->rows;

        if ($this->options['header']) {
            array_unshift($rows, new Separator($this->options));
            array_unshift($rows, new Header($this->options));
        }

        foreach ($this->columns as $column) {
            foreach ($rows as $row) {
                $column->scan($row);
            }
        }

        $rendering = array();
        foreach ($rows as $row) {
            $rowRendering = array();
            foreach ($this->columns as $column) {
                $rowRendering[] = $row->render($column);
            }
            $rendering[] = $this->options['indent'].implode($this->options['gutter'], $rowRendering);
        }

        return implode("\n", $rendering);
    }

    /**
     * @return Formatter
     */
    public function write(array $rows = null)
    {
        $this->output->writeln("\n".$this->render($rows)."\n");

        return $this;
    }

    /**
     * @param OutputFormatterInterface $formatter
     * @return Formatter
     */
    protected function setupOutputFormatter(OutputFormatterInterface $formatter)
    {
        if (!$formatter->hasStyle('label')) {
            $formatter->setStyle('label', new OutputFormatterStyle('white'));
        }

        if (!$formatter->hasStyle('separator')) {
            $formatter->setStyle('separator', new OutputFormatterStyle('white'));
        }

        return $this;
    }
}
