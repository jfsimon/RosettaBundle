<?php

namespace Bundle\RosettaBundle\Service\Scanner\Twig;

use Symfony\Bundle\TwigBundle\TokenParser\TransChoiceTokenParser as BaseTransChoiceTokenParser;
use Symfony\Bundle\TwigBundle\Node\TransNode;

class TransChoiceTokenParser extends BaseTransChoiceTokenParser
{
    protected $messages;

    public function __construct()
    {
        $this->messages = array();
    }

    public function parse(\Twig_Token $token)
    {
        $node = parent::parse($token);

        $this->messages[] = array(
            'text' => $node->getNode('body'),
            'parameters' => array_keys($node->getNode('vars')),
            'domain' => $node->getNode('domain'),
            'choice' => true
        );
    }

    public function getMessages()
    {
        return $this->messages;
    }
}
