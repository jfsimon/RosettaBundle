<?php

namespace Bundle\RosettaBundle\Scanner;

use Symfony\Component\Translation\Interval;

class Message
{
    protected $domain;
    protected $text;
    protected $choice;
    protected $parameters;

    const CHOICE_STRING_SEPARATOR = '|';

    public function __construct($text, $choice=false, $domain='messages')
    {
        $this->text = trim($text);
        $this->choice = (bool)$choice;
        $this->domain = $domain;
        $this->parameters = array();
    }

    public function addParameter($parameter)
    {
        $this->parameters[] = $parameter;
    }

    public function isChoice()
    {
        return $this->choice;
    }

    public function hasParameters()
    {
        return count($this->parameters);
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function getText()
    {
        return $this->text;
    }

    public function getChoiceMessage()
    {
        if(! $this->isChoice()) {
            return null;
        }

        return $this->parseChoiceMessages();
    }

    protected function parseChoiceMessages()
    {
        $messages = array();
        $parts = explode(self::CHOICE_STRING_SEPARATOR, $this->string);

        foreach($parts as $part) {
            $part = trim($part);

            if (preg_match('/^(?<interval>'.Interval::getIntervalRegexp().')\s+(?<message>.+?)$/x', $part, $matches)) {
                $messages[$matches['interval']] = $matches['message'];
            } elseif (preg_match('/^(\w+)\: +(.+)$/', $part, $matches)) {
                $messages[$matches[1]] = $matches[2];
            } else {
                $messages[] = $part;
            }
        }

        return $messages;
    }
}