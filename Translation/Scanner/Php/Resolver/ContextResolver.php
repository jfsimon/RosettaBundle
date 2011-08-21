<?php

namespace BeSimple\RosettaBundle\Translation\Scanner\Php\Resolver;

use BeSimple\RosettaBundle\Translation\Scanner\Php\Parser\TokenStack;
use BeSimple\RosettaBundle\Translation\Scanner\Php\Parser\Token;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class ContextResolver
{
    /**
     * @var array
     */
    private $context = array();

    /**
     * Constructor.
     *
     * @param array $context
     */
    public function __construct(array $context = array())
    {
        $this->setContext($context);
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(TokenStack $tokens)
    {
        $tokens->rewind();

        while ($token = $tokens->next()) {
            if ($token->getType() === T_VARIABLE) {
                if (isset($this->context[$token->getContent()])) {
                    $tokens->set($tokens->cursor(), $this->context[$token->getContent()]);
                }
            }
        }
    }

    /**
     * @param array $context An array of variable/value pairs
     *
     * @return ContextResolver This ContextResolver instance
     */
    public function setContext(array $context)
    {
        $this->context = array();

        foreach ($context as $var => $scalar) {
            $tokens = token_get_all($scalar);
            $this->context['$'.$var] = new Token($tokens[0]);
        }

        return $this;
    }

    /**
     * @return array An array of variable/value pairs
     */
    public function getContext()
    {
        return $this->context;
    }
}
