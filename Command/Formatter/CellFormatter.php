<?php

namespace BeSimple\RosettaBundle\Command\Formatter;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class CellFormatter
{
    const ALIGN_LEFT   = 'left';
    const ALIGN_RIGHT  = 'right';
    const ALIGN_CENTER = 'center';

    /**
     * @var StyleFormatter
     */
    private $styleFormatter;

    /**
     * @var string
     */
    private $alignment;

    /**
     * @var int|null
     */
    private $length;

    /**
     * Constructor.
     *
     * @param StyleFormatter $styleFormatter
     * @param string         $alignment
     * @param null           $length
     */
    public function __construct(StyleFormatter $styleFormatter, $alignment = self::ALIGN_LEFT, $length = null)
    {
        $this->styleFormatter = $styleFormatter;
        $this->alignment      = $alignment;
        $this->length         = $length;
    }

    /**
     * Formats cell.
     *
     * @throws \InvalidArgumentException
     *
     * @param string $text
     *
     * @return string
     */
    public function format($text)
    {
        $text   = $this->styleFormatter->flatten($text);
        $spaces = $this->length - $this->count($text);

        if ($spaces < 0 || is_null($this->length)) {
            return $text;
        }

        if ($this->alignment === self::ALIGN_LEFT) {
            return $text.str_repeat(' ', $spaces);
        }

        if ($this->alignment === self::ALIGN_RIGHT) {
            return str_repeat(' ', $spaces).$text;
        }

        if ($this->alignment === self::ALIGN_RIGHT) {
            $left  = ceil($spaces / 2);
            $right = $spaces - $left;

            return str_repeat(' ', $left).$text.str_repeat(' ', $right);
        }

        throw new \InvalidArgumentException('Invalid alignment: '.$this->alignment.'; valid values are left, right or center.');
    }

    /**
     * Fills cell with repeated string.
     *
     * @param string $string
     * @param int    $length
     *
     * @return string
     */
    public function fill($string)
    {
        $strLen = strlen($string);

        return substr(str_repeat($string, ceil($this->length / $strLen)), 0, $this->length);
    }

    /**
     * Sets length to max of stored length or given text length.
     *
     * @param $text
     *
     * @return CellFormatter
     */
    public function scanLength($text)
    {
        $this->length = max($this->length ?: 0, $this->count($text));

        return $this;
    }

    /**
     * @param string $alignment
     *
     * @return CellFormatter
     */
    public function setAlignment($alignment)
    {
        $this->alignment = $alignment;

        return $this;
    }

    /**
     * @return string
     */
    public function getAlignment()
    {
        return $this->alignment;
    }

    /**
     * @param int|null $length
     *
     * @return CellFormatter
     */
    public function setLength($length)
    {
        $this->length = $length;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param StyleFormatter $styleFormatter
     *
     * @return CellFormatter
     */
    public function setStyleFormatter($styleFormatter)
    {
        $this->styleFormatter = $styleFormatter;

        return $this;
    }

    /**
     * @return \BeSimple\RosettaBundle\Command\Formatter\StyleFormatter
     */
    public function getStyleFormatter()
    {
        return $this->styleFormatter;
    }

    /**
     * @param string $text
     *
     * @return int
     */
    private function count($text)
    {
        $flat = $this->styleFormatter->raw($text);

        return strlen($flat);
    }
}
