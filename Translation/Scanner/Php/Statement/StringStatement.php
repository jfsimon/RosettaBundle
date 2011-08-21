<?php

namespace BeSimple\RosettaBundle\Translation\Scanner\Php\Statement;

use BeSimple\RosettaBundle\Translation\Scanner\Php\Parser\TokenStack;
use BeSimple\RosettaBundle\Translation\Scanner\Php\Parser\ParserException;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class StringStatement
{
    /**
     * @var string
     */
    protected $string;

    /**
     * @var int
     */
    protected $line;

    /**
     * Constructor.
     *
     * @throws \InvalidArgumentException
     *
     * @param TokenStack $tokens
     */
    public function __construct(TokenStack $tokens)
    {
        if (!$this->validate($tokens)) {
            throw new ParserException('A string was expected', $tokens);
        }

        $this->parse($tokens);
    }

    /**
     * @return string The string
     */
    public function getString()
    {
        return $this->string;
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
     * @return bool
     */
    protected function validate(TokenStack $tokens)
    {
        if ($tokens->count() !== 1) {
            return false;
        }

        $token = $tokens->get(0);

        if (!$token->getType() === T_CONSTANT_ENCAPSED_STRING) {
            return false;
        }

        return true;
    }

    /**
     * @param TokenStack $tokens
     */
    protected function parse(TokenStack $tokens)
    {
        $this->line = $tokens->get(0)->getLine();
        $content    = $tokens->get(0)->getContent();
        $quote      = substr($content, 0, 1);

        $this->string = trim(str_replace('\\'.$quote, '', $content), $quote);
    }
}
