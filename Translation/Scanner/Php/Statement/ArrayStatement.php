<?php

namespace BeSimple\RosettaBundle\Translation\Scanner\Php\Statement;

use BeSimple\RosettaBundle\Translation\Scanner\Php\Parser\Token;
use BeSimple\RosettaBundle\Translation\Scanner\Php\Parser\TokenStack;
use BeSimple\RosettaBundle\Translation\Scanner\Php\Parser\ParserException;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class ArrayStatement
{
    /**
     * @var array
     */
    private $items;

    /**
     * @var int
     */
    private $line;

    /**
     * Constructor.
     *
     * @throws \InvalidArgumentException
     *
     * @param TokenStack $tokens A TokenStack instance
     */
    public function __construct(TokenStack $tokens)
    {
        if (!$this->validate($tokens)) {
            throw new ParserException('Array structure should have the form "array({tokens})"', $tokens);
        }

        $this->parse($tokens);
    }

    /**
     * Returns parsed key/value of the array
     *
     * @return array An array of arrays
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Returns parsed keys of the array
     *
     * @return array An array of TokenStack
     */
    public function getKeys()
    {
        $keys = array();

        foreach ($this->items as $item) {
            $keys[] = $item['key'];
        }

        return $keys;
    }

    /**
     * Returns parsed values of the array
     *
     * @return array An array of TokenStack
     */
    public function getValues()
    {
        $values = array();

        foreach ($this->items as $item) {
            $values[] = $item['value'];
        }

        return $values;
    }

    /**
     * @return int Original source code line number
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * @param TokenStack $tokens
     *
     * @return bool
     */
    private function validate(TokenStack $tokens)
    {
        if ($tokens->count() < 3) {
            return false;
        }

        $firstToken  = $tokens->get(0);
        $secondToken = $tokens->get(1);
        $lastToken   = $tokens->get(-1);

        return $firstToken->getContent() == 'array' && $secondToken->getContent() === '(' && $lastToken->getContent() === ')';
    }

    /**
     * @param TokenStack $tokens
     */
    private function parse(TokenStack $tokens)
    {
        $tokens->rewind();

        $firstToken = $tokens->next();
        $this->line = $firstToken->getLine();

        $tokens->shift(2);
        $tokens->pop();

        $this->items = array();
        while ($tokens->count() > 0) {
            $value = $tokens->extract(new Token(','));
            $value->next();

            $key = $value->extract(new Token(array(T_DOUBLE_ARROW, '=>', null)));

            $tokens->shift($value->count() + 1);

            if ($key->count() > 0) {
                $value->shift($key->count() + 1);
                $this->items[] = array('key' => $key, 'value' => $value);
            } else {
                $this->items[] = array('key' => null, 'value' => $value);
            }
        }
    }
}
