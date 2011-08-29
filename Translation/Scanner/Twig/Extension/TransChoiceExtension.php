<?php

namespace BeSimple\RosettaBundle\Translation\Scanner\Twig\Extension;

use BeSimple\RosettaBundle\Translation\Scanner\Twig\TokenParser\TransChoiceTokenParser;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class TransChoiceExtension extends \Twig_Extension implements ExtensionInterface
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
        $this->tokenParser = new TransChoiceTokenParser();
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
        return array('transchoice' => new \Twig_Filter_Method($this, 'scan'));
    }

    /**
     * Scans a trans transChoice call
     *
     * @param string      $message   The message text
     * @param array       $arguments The message arguments
     * @param string|null $domain    The message domain
     */
    public function scan($message, $count, array $arguments = array(), $domain = null)
    {
        $this->messages[] = array(
            'text'       => $message,
            'parameters' => count($arguments) ? array_keys($arguments) : null,
            'domain'     => $domain ?: 'messages',
            'isChoice'   => true,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'trans_choice_scanner';
    }
}
