<?php

namespace Bundle\RosettaBundle\Service\Scanner;

class PhpScanner extends BaseScanner implements ScannerInterface,  \IteratorAggregate
{
    protected function parseMessages($content)
    {
        $messages = array();

        $remain = $content;
        foreach($this->getMethodCalls($content, 'trans') as $call) {
            $remain = substr($remain, strpos($remain, $call) + strlen($call) - 1);
            $messages[] = $this->parseMessage($remain, false);
        }

        $remain = $content;
        foreach($this->getMethodCalls($content, 'transChoice') as $call) {
            $remain = substr($remain, strpos($remain, $call) + strlen($call) - 1);
            $messages[] = $this->parseMessage($remain, true);
        }

        return $messages;
    }

    protected function getMethodCalls($content, $method)
    {
        // we check method calls with opening quote at first parameter
        if(! preg_match('/->\\s*'.$method.'\\s*(\\s*[\'|"]/', $content, $matches)) {
            return array();
        }

        $calls = array();

        foreach($matches as $index => $match) {
            if($index > 0) {
                $calls[] = $match;
            }
        }

        return $calls;
    }

    protected function parseMessage($content, $isChoice)
    {
        $message = array('text' => '', 'parameters' => array(), 'domain' => 'messages', 'choice' => $isChoice);
        $parser = new PhpParser($content);

        // get the text
        $text = $parser->getString();
        if(is_null($text)) {
            return null;
        }
        $message['text'] = $text;

        // has another parameter ?
        if(! $parser->hasParametersSeparator()) {
            return $message;
        }

        // if isChoice, ignore 2nd parameter
        if($isChoice) {
            $parser->ignoreParameter();

            // has another parameter ?
            if(! $parser->hasParametersSeparator()) {
                return $message;
            }
        }

        // get the parameters
        $parameters = $parser->getArrayKeys();
        if(is_array($parameters)) {
           $message['parameters'] = $parameters;
        }

        // has another parameter ?
        if(! $parser->hasParametersSeparator()) {
            return $message;
        }

        // get the domain
        $domain = $parser->getString();
        if(! is_null($domain)) {
            $message['domain'] = $domain;
        }
    }
}