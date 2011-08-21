<?php

namespace BeSimple\RosettaBundle\Translation\Scanner\Twig\TokenParser;

use Symfony\Bridge\Twig\TokenParser\TransTokenParser as BaseTransTokenParser;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class TransTokenParser extends BaseTransTokenParser
{
    /**
     * @var array
     */
    protected $messages;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->messages = array();
    }

    /**
     * {@inheritdoc}
     */
    public function parse(\Twig_Token $token)
    {
        $node       = parent::parse($token);
        $parameters = array();

        foreach ($node->getNode('vars') as $argument => $value) {
            $parameters[] = $argument;
        }

        $this->messages[] = array(
            'text'       => $node->getNode('body')->getAttribute('data'),
            'parameters' => count($parameters) ? $parameters : null,
            'domain'     => $node->getNode('domain')->getAttribute('value'),
            'isChoice'   => false,
        );
    }

    /**
     * @return array Scanned messages
     */
    public function getMessages()
    {
        return $this->messages;
    }
}
