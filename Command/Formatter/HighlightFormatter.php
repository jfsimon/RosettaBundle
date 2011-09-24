<?php

namespace BeSimple\RosettaBundle\Command\Formatter;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class HighlightFormatter
{
    /**
     * @var StyleFormatter
     */
    private $styleFormatter;

    /**
     * @var array
     */
    private $highlights;

    /**
     * Constructor.
     *
     * @param StyleFormatter $styleFormatter
     * @param array          $highlights
     */
    public function __construct(StyleFormatter $styleFormatter, array $highlights = array())
    {
        $this->styleFormatter = $styleFormatter;
        $this->highlights     = $highlights;
    }

    /**
     * Highlights sub-strings in text..
     *
     * @param $text
     *
     * @return string
     */
    public function format($text)
    {
        foreach ($this->highlights as $string => $style) {
            $text = str_replace($string, sprintf('<%s>%s</%s>', $style, $string, $style), $text);
        }

        return $this->styleFormatter->flatten($text);
    }

    /**
     * @param array $highlights
     *
     * @return HighlightFormatter
     */
    public function setHighlights($highlights)
    {
        $this->highlights = $highlights;

        return $this;
    }

    /**
     * @return array
     */
    public function getHighlights()
    {
        return $this->highlights;
    }

    /**
     * @param StyleFormatter $styleFormatter
     *
     * @return HighlightFormatter
     */
    public function setStyleFormatter($styleFormatter)
    {
        $this->styleFormatter = $styleFormatter;

        return $this;
    }

    /**
     * @return StyleFormatter
     */
    public function getStyleFormatter()
    {
        return $this->styleFormatter;
    }
}
