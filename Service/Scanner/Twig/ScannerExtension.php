<?php

namespace Bundle\RosettaBundle\Service\Scanner\Twig;

use Bundle\RosettaBundle\Service\Scanner\Twig\TransTokenParser;
use Bundle\RosettaBundle\Service\Scanner\Twig\TransChoiceTokenParser;

class ScannerExtension extends \Twig_Extension
{
    protected $messages;
    protected $transTokenParser;
    protected $transChoiceTokenParser;

    public function __construct()
    {
        $this->messages = array();
        $this->transTokenParser = new TransTokenParser();
        $this->transChoiceTokenParser = new TransChoiceTokenParser();
    }

    public function getMessages()
    {
        return array_merge(
            $this->messages,
            $this->transTokenParser->getMessages(),
            $this->transChoiceTokenParser->getMessages()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            'trans' => new \Twig_Filter_Method($this, 'scan'),
        );
    }

    /**
     * Returns the token parser instance to add to the existing list.
     *
     * @return array An array of Twig_TokenParser instances
     */
    public function getTokenParsers()
    {
        return array(
            $this->transTokenParser,
            $this->transChoiceTokenParser,
        );
    }

    public function scan($message, array $arguments = array(), $domain = "messages")
    {
        $this->messages[] = array(
            'text' => $message,
            'parameters' => array_keys($arguments),
            'domain' => $domain,
            'choice' => false
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'scanner';
    }
}
