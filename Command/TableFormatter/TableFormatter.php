<?php

namespace BeSimple\RosettaBundle\Command\TableFormatter;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class TableFormatter
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
     * @param OutputInterface $output
     * @param array $options
     *
     * @return TableFormatter
     */
    static public function create(OutputInterface $output, array $options = array())
    {
        return new static($output, $options);
    }

    /**
     * @param TableColumn $column
     *
     * @return TableFormatter
     */
    public function addColumn(TableColumn $column)
    {
        $this->columns[] = $column;

        return $this;
    }

    /**
     * @param TableRowInterface $row
     *
     * @return TableFormatter
     */
    public function addRow(TableRowInterface $row)
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
            array_unshift($rows, new TableSeparator($this->options));
            array_unshift($rows, new TableHeader($this->options));
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
     *
     * @return TableFormatter
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
