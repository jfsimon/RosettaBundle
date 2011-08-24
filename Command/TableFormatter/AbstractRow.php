<?php

namespace BeSimple\RosettaBundle\Command\TableFormatter;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class AbstractRow
{
    /**
     * @var array
     */
    protected $options = array();

    /**
     * @var array
     */
    protected $highlights = array();

    /**
     * @param array $options
     * @param array $highlights
     */
    public function __construct(array $options = array(), array $highlights = array())
    {
        $this->options    = array_merge(Formatter::$defaults, $options);
        $this->highlights = $highlights;
    }

    /**
     * @param array $options
     * @return AbstractRow
     */
    public function setOptions(array $options)
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param string $string
     * @param string $style
     * @return AbstractRow
     */
    public function highlight($string, $style = 'error', array $fields = null)
    {
        $this->highlights[$string] = array(
            'style' => $style,
            'fields' => $fields
        );

        return $this;
    }

    /**
     * @param Column $column
     * @param string $value
     * @param null $style
     * @return string
     */
    protected function renderValue(Column $column, $value, $style = null)
    {
        $value = $value ?: $this->options['null'];

        return ($style ? '<'.$style.'>' : '')
            .$this->renderHighlights($value, $column->getKey(), $style)
            .($style ? '</'.$style.'>' : '')
            .str_repeat(' ', $column->getLength() - strlen($value));
    }

    /**
     * @param $value string
     * @return string
     */
    protected function renderHighlights($value, $field, $oldStyle)
    {
        foreach ($this->highlights as $string => $highlight) {
            if (!is_array($highlight['fields']) || in_array($field, $highlight['fields'])) {
                $replacement = ($oldStyle ? '</'.$oldStyle.'>' : '')
                    .'<'.$highlight['style'].'>'.$string.'</'.$highlight['style'].'>'
                    .($oldStyle ? '<'.$oldStyle.'>' : '');

                $value = str_replace($string, $replacement, $value);
            }
        }

        return $value;
    }
}
