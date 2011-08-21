<?php

namespace BeSimple\RosettaBundle\Translation\Scanner\Twig\Extension;

use BeSimple\RosettaBundle\Translation\Scanner\Twig\TokenParser\TransTokenParser;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class TransExtension extends \Twig_Extension implements ExtensionInterface
{
    /**
     * @var array
     */
    protected $messages;

    /**
     * @var TransTokenParser
     */
    protected $tokenParser;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->messages    = array();
        $this->tokenParser = new TransTokenParser();
    }

    /**
     * {@inheritdoc}
     */
    public function getMessages()
    {
        return array_merge($this->messages, $this->tokenParser->getMessages());
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array('trans' => new \Twig_Filter_Method($this, 'scan'));
    }

    /**
     * {@inheritdoc}
     */
    public function getTokenParsers()
    {
        return array($this->tokenParser);
    }

    /**
     * Scans a trans filter call
     *
     * @param string      $message   The message text
     * @param array       $arguments The message arguments
     * @param string|null $domain    The message domain
     */
    public function scan($message, array $arguments = array(), $domain = null)
    {
        $this->messages[] = array(
            'text'       => $message,
            'parameters' => count($arguments) ? array_keys($arguments) : null,
            'domain'     => $domain ?: 'messages',
            'isChoice'   => false,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'trans_scanner';
    }
}
