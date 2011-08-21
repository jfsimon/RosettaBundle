<?php

namespace BeSimple\RosettaBundle\Translation\Scanner\Php\Statement;

use BeSimple\RosettaBundle\Translation\Scanner\Php\Parser\Token;
use BeSimple\RosettaBundle\Translation\Scanner\Php\Parser\TokenStack;
use BeSimple\RosettaBundle\Translation\Scanner\Php\Parser\ParserException;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class FunctionCallStatement
{
    /**
     * @var array
     */
    private $name;

    /**
     * @var array
     */
    private $arguments;

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
            throw new ParserException('Function call should have the form {string}({tokens})', $tokens);
        }

        $this->parse($tokens);
    }

    /**
     * @return string Function name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array An array of TokenStack instances
     */
    public function getArguments()
    {
        return $this->arguments;
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

        return $firstToken->getType() == T_STRING && $secondToken->getContent() === '(' && $lastToken->getContent() === ')';
    }

    /**
     * @param TokenStack $tokens
     */
    private function parse(TokenStack $tokens)
    {
        $tokens->rewind();

        $firstToken = $tokens->next();
        $this->name = $firstToken->getContent();
        $this->line = $firstToken->getLine();

        $tokens->shift(2);
        $tokens->pop();

        $this->arguments = array();
        while ($tokens->count() > 0) {
            $this->arguments[] = $argument = $tokens->extract(new Token(','));
            $tokens->shift($argument->count() + 1);
        }
    }
}
