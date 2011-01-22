<?php

namespace Bundle\RosettaBundle\Service\Scanner;

/**
 * Not proud of this, but should work!
 */

class PhpParser
{
    protected $tokens;

    public function __construct($content)
    {
        $this->tokens = token_get_all($content);
    }

    public function getString()
    {
        $this->ignoreWhite();

        try {
            $token = $this->shiftToken(array(T_STRING));
            return $token[1];
        } catch(\RuntimeException $e) {
            return null;
        }
    }

    public function hasParametersSeparator()
    {
        $this->ignoreWhite();

        try {
            $this->shiftString(array(','));
            return true;
        } catch(\RuntimeException $e) {
            return false;
        }
    }

    public function ignoreParameter()
    {
        while(! in_array($this->tokens[0], array(',', ')'))) {
            $this->tokens = array_shift($this->tokens);
        }
    }

    public function getArrayKeys()
    {
        $this->ignoreWhite();

        // need infos
    }

    protected function ignoreWhite()
    {
        while(true) {
            try {
                $this->shift(array(T_BAD_CHARACTER, T_COMMENT, T_DOC_COMMENT));
            } catch(\RuntimeException $e) {
                break;
            }
        }
    }

    protected function shiftToken(array $expected)
    {
        if(empty($this->tokens)) {
            throw new \RuntimeException();
        }

        if(! in_array($this->tokens[0][0], $expected)) {
            throw new \RuntimeException();
        }

        $token = $this->tokens[0];
        $this->tokens = array_shift($this->tokens);

        return $token;
    }

    protected function shiftString(array $expected)
    {
        if(empty($this->tokens)) {
            throw new \RuntimeException();
        }

        $string = trim($this->tokens[0], " \n\r\t");

        if(! in_array($string, $expected)) {
            throw new \RuntimeException();
        }

        $this->tokens = array_shift($this->tokens);

        return $string;
    }
}