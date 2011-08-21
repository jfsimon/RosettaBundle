<?php

namespace BeSimple\RosettaBundle\Translation\Scanner\Php\Parser;

use BeSimple\RosettaBundle\Translation\Scanner\Php\Statement\FunctionCallStatement;

/**
 * PHP source code parser.
 *
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class Parser
{
    /**
     * @var TokenStack
     */
    private $tokens;

    /**
     * Constructor.
     *
     * @param TokenStack|null $tokens A TokenStack instance of null
     */
    public function __construct(TokenStack $tokens = null)
    {
        $this->tokens = $tokens ?: new TokenStack();
    }

    /**
     * Parse given source code.
     *
     * @param string $source    A source code
     * @param array  $resolvers An array of resolvers
     *
     * @return Parser This parser instance
     */
    public function parse($source, array $resolvers = array())
    {
        foreach (token_get_all($source) as $token) {
            $this->tokens->push($token);
        }

        foreach ($resolvers as $resolver)
        {
            $resolver->resolve($this->tokens);
        }

        return $this;
    }

    /**
     * Extract function call statements.
     *
     * @param array|string $functionName Function name
     *
     * @return array An array of FunctionCallStatement
     */
    public function extractFunctionCalls($functionName)
    {
        $functionCalls = array();

        $this->tokens->rewind();

        while ($token = $this->tokens->next()) {
            $nextToken = $this->tokens->get($this->tokens->cursor() + 1);

            if ($token->getType() === T_STRING && $token->getContent() === $functionName && $nextToken->getContent() === '(') {
                $functionCalls[] = new FunctionCallStatement($this->tokens->extract(new Token(')'), true));
            }
        }

        return $functionCalls;
    }

    /**
     * Extract method call statements.
     *
     * @param array|string $methodName A method name
     *
     * @return array An array of FunctionCallStatement
     */
    public function extractMethodCalls($methodName)
    {
        $functionCalls = array();
        $isAccessed    = false;

        $this->tokens->rewind();

        while ($token = $this->tokens->next()) {
            if ($token->getType() === T_OBJECT_OPERATOR && $token->getContent() === '->') {
                $isAccessed = true;
                continue;
            }

            $nextToken = $this->tokens->get($this->tokens->cursor() + 1);

            if ($isAccessed && $token->getType() === T_STRING && $token->getContent() === $methodName && $nextToken->getContent() === '(') {
                $functionCalls[] = new FunctionCallStatement($this->tokens->extract(new Token(')'), true));
            }

            $isAccessed = false;
        }

        return $functionCalls;
    }
}
