<?php

namespace BeSimple\RosettaBundle\Command\Helper;

use Symfony\Component\Console\Helper\FormatterHelper as BaseFormatterHelper;
use Symfony\Component\Console\Output\OutputInterface;
use BeSimple\RosettaBundle\Command\Formatter\StyleFormatter;
use BeSimple\RosettaBundle\Command\Formatter\CellFormatter;
use BeSimple\RosettaBundle\Command\Formatter\RowFormatter;
use BeSimple\RosettaBundle\Command\Formatter\TableFormatter;
use BeSimple\RosettaBundle\Command\Formatter\HighlightFormatter;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class FormatterHelper extends BaseFormatterHelper
{
    public function formatHeader($title, $description = null)
    {
        $str = "\n".$this->formatBlock($title, 'bg=blue;fg=white', true);

        if ($description) {
            $str.= "\n\n".$description;
        }

        return $str."\n\n";
    }

    public function formatSummary($text, $success = true)
    {
        return "\n".$this->formatBlock($text, $success ? 'bg=green;fg=black' : 'bg=red;fg=white', true)."\n\n";
    }

    public function formatTable(array $header, array $body)
    {
        $styleFormatter = new StyleFormatter();
        $cellFormatters = array();
        $headerLabels   = array();

        foreach ($header as $label => $alignment) {
            $cellFormatters[] = new CellFormatter($styleFormatter, $alignment);
            $headerLabels[]   = $label;
        }

        $rowFormatter   = new RowFormatter($cellFormatters, '  ', '  ');
        $tableFormatter = new TableFormatter($rowFormatter, $headerLabels);

        return "\n".$tableFormatter->format($body)."\n\n";
    }

    public function formatHighlight($text, array $strings, $style)
    {
        $highlights = array();

        foreach ($strings as $string) {
            $highlights[$string] = $style;
        }

        $styleFormatter     = new StyleFormatter();
        $highlightFormatter = new HighlightFormatter($styleFormatter, $highlights);

        return $highlightFormatter->format($text);
    }
}
