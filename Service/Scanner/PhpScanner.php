<?php

namespace Bundle\RosettaBundle\Service\Scanner;

class PhpScanner extends BaseScanner implements ScannerInterface,  \IteratorAggregate
{
    protected function parseMessages($content)
    {
        $messages = array();
        $tokens = token_get_all($content);
        $accessor = false;

        while($token = array_shift($tokens)) {

            if($this->ignoreToken($token)) {
                continue;
            }

            if($accessor) {
                if($token[0] === T_STRING && $token[1] === 'trans') {
                    $messages[] = $this->parseArguments($tokens, false);
                    continue;
                }

                if($token[0] === T_STRING && $token[1] === 'transChoice') {
                    $messages[] = $this->parseArguments($tokens, true);
                    continue;
                }

                $accessor = false;
            }

            if($token[0] === T_OBJECT_OPERATOR && $token[1] === '->') {
                $accessor = true;
            }
        }

        return $messages;
    }

    protected function parseArguments(array &$tokens, $isChoice=false)
    {
        $message = array('text' => '', 'parameters' => array(), 'domain' => 'messages', 'choice' => $isChoice);

        $open = $this->shiftToken($tokens);
        if(! $open === '(') {
            return null;
        }

        $message['text'] = $this->shiftString($tokens);
        if(is_null($message['text'])) {
            return null;
        }

        if(! $this->shiftParametersSeprator($tokens)) {
            return $message;
        }

        if($isChoice) {
            $this->shiftParameter($tokens);

            if(! $this->shiftParametersSeprator($tokens)) {
                return $message;
            }
        }

        $parameters = $this->shiftArrayKeys($tokens);
        if(is_array($parameters)) {
            $message['parameters'] = $parameters;
        }

        if(! $this->shiftParametersSeprator($tokens)) {
            return $message;
        }

        $domain = $this->shiftString($tokens);
        if($domain) {
            $message['domain'] = $domain;
        }

        return $message;
    }

    protected function ignoreToken($token)
    {
        if(! is_array($token)) {
            return false;
        }

        $t = $token[0];

        return $t === T_WHITESPACE
            || $t === T_BAD_CHARACTER
            || $t === T_COMMENT
            || $t === T_DOC_COMMENT;
    }

    protected function shiftToken(array &$tokens)
    {
        while($this->ignoreToken($tokens[0])) {
            array_shift($tokens);
        }

        return array_shift($tokens);
    }

    protected function shiftString(array &$tokens)
    {
        $token = $this->shiftToken($tokens);

        if(! in_array($token[0], array(T_CONSTANT_ENCAPSED_STRING))) {
            return null;
        }

        return trim($token[1], '\'"');
    }

    protected function shiftParametersSeprator(array &$tokens)
    {
        $token = $this->shiftToken($tokens);

        return $token === ',';
    }

    protected function shiftParameter(array &$tokens)
    {
        $level = 0;

        while(! in_array($tokens[0], array(',', ')')) || $level > 0) {
            if($tokens[0] === '(') {
                $level ++;
            }

            if($tokens[0] === ')') {
                $level --;
            }

            array_shift($tokens);
        }
    }

    protected function shiftArrayKeys(array &$tokens)
    {
        $keys = array();

        $token = $this->shiftToken($tokens);
        if(! $token[0] === T_ARRAY) {
            return null;
        }

        $token = $this->shiftToken($tokens);
        if(! $token === '(') {
            return null;
        }

        while($key = $this->shiftArrayKey($tokens)) {
            if($key) {
                $keys[] = $key;
            } else {
                return null;
            }

            $token = $this->shiftToken($tokens);

            if($token !== ',') {
                return $keys;
            }
        }
    }

    protected function shiftArrayKey(array &$tokens)
    {
        $key = null;

        $string = $this->shiftString($tokens);
        if($string) {
            $key = $string;
        } else {
            return null;
        }

        $token = $this->shiftToken($tokens);
        if(! $token[0] === T_DOUBLE_ARROW) {
            return null;
        }

        $this->shiftParameter($tokens);

        return $key;
    }
}