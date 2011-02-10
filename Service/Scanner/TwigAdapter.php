<?php

namespace Bundle\RosettaBundle\Service\Scanner;

use Bundle\RosettaBundle\Service\Scanner\Twig\ScannerExtension;

class TwigAdapter extends Adapter implements AdapterInterface
{
    protected $extension;
    protected $environment;

    public function __construct()
    {
        parent::__construct();

        $this->extension = new ScannerExtension();

        $this->environment = new \Twig_Environment();
        $this->environment->setExtensions(array($this->extension));
        $this->environment->setParser(new \Twig_Parser($this->environment));
    }

    public function parseMessages($content)
    {
        $tokens = $this->environment->tokenize($content);
        $this->environment->parse($tokens);

        return $this->extension->getMessages();
    }
}