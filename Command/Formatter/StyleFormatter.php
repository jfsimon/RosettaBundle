<?php

namespace BeSimple\RosettaBundle\Command\Formatter;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class StyleFormatter
{
    const TAG_PATTERN = '#</?([a-z][a-z0-9_=;-]*)>#is';

    /**
     * @var OutputInterface|null
     */
    private $output;

    /**
     * Contructor.
     *
     * @param OutputInterface|null $output
     */
    public function __construct(OutputInterface $output = null)
    {
        $this->output = $output;
    }

    /**
     * Returns raw text (without style).
     *
     * @param string $text
     *
     * @return string
     */
    public function raw($text)
    {
        $raw = '';

        foreach ($this->tokenize($text) as $token) {
            if ($token['type'] === 'text') {
                $raw .= $token['content'];
            }
        }

        return $raw;
    }

    /**
     * Flattens (unworking) nested styles.
     *
     * @throws \RuntimeException
     *
     * @param string$text
     *
     * @return string
     */
    public function flatten($text)
    {
        $styles  = array();
        $flatten = array();

        foreach ($this->tokenize($text) as $token) {
            if ($token['type'] === 'open') {
                if (count($styles) > 0) {
                    $flatten[] = array('type' => 'close', 'content' => end($styles));
                }

                $styles[]  = $token['content'];
                $flatten[] = $token;
            }

            else if ($token['type'] === 'close') {
                if (count($styles) < 1 || array_pop($styles) !== $token['content']) {
                    throw new \RuntimeException('Invalid nested style: '.$token['content'].'.');
                }

                $flatten[] = $token;

                if ($style = end($styles)) {
                    $flatten[] = array('type' => 'open', 'content' => $style);
                }
            }

            else {
                $flatten[] = $token;
            }
        }

        return $this->format($this->clean($flatten));
    }

    /**
     * @param OutputInterface|null $output
     *
     * @return StyleFormatter
     */
    public function setOutput($output)
    {
        $this->output = $output;

        return $this;
    }

    /**
     * @return OutputInterface|null
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @param string $text
     *
     * @return array
     */
    private function tokenize($text)
    {
        $tokens = array();

        while (strlen($text) > 0) {
            $matches = array();

            if (preg_match(self::TAG_PATTERN, $text, $matches, PREG_OFFSET_CAPTURE)) {
                $tokens[] = array(
                    'type' => 'text',
                    'content' => substr($text, 0, $matches[0][1])
                );

                $tokens[] = array(
                    'type'    => substr($matches[0][0], 1, 1) === '/' ? 'close' : 'open',
                    'content' => $matches[1][0]
                );

                $text = substr($text, strlen($matches[0][0]) + $matches[0][1]);
            }

            else {
                $tokens[] = array('type' => 'text', 'content' => $text);
                $text     = '';
            }
        }

        return $this->validate($tokens);
    }

    /**
     * @param array $tokens
     *
     * @return array
     */
    private function validate(array $tokens)
    {
        foreach ($tokens as &$token) {
            if (in_array($token['type'], array('open', 'close'))) {
                $style = preg_replace(self::TAG_PATTERN, '$1', $token['content']);

                if (!$this->isStyle($style)) {
                    $token['content'] = $this->format(array($token));
                    $token['type'] = 'text';
                }
            }
        }

        return $tokens;
    }

    /**
     * @param array $tokens
     *
     * @return string
     */
    private function format(array $tokens)
    {
        $formatted = '';

        foreach ($tokens as $token) {
            switch ($token['type']) {
                case 'open':
                    $formatted.= '<'.$token['content'].'>';
                    break;
                case 'close':
                    $formatted.= '</'.$token['content'].'>';
                    break;
                default:
                    $formatted.= $token['content'];
            }
        }

        return $formatted;
    }

    /**
     * @param string $string
     *
     * @return bool
     */
    private function isStyle($string)
    {
        if (preg_match('#([fb]g=[a-z]+?;?)+#is', $string)) {
            return true;
        }

        return is_null($this->output) || $this->output->getFormatter()->hasStyle($string);
    }

    /**
     * Cleans empty style tags & empty text tokens.
     *
     * @param array $tokens
     *
     * @return array
     */
    private function clean(array $tokens)
    {
        $clean = array();
        $open  = null;

        foreach ($tokens as $token) {
            if ($token['type'] === 'text' && $token['content'] === '') {
                continue;
            }

            $append = true;

            if (is_array($open)) {
                if ($token['type'] === 'close' && $token['content'] === $open['content']) {
                    $append = false;
                } else {
                    $clean[] = $open;
                }

                $open = null;
            }

            if ($token['type'] === 'open') {
                $open = $token;
            } else if($append) {
                $clean[] = $token;
            }
        }

        return $clean;
    }
}
