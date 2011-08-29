<?php

namespace BeSimple\RosettaBundle\Translation\Scanner\Twig;

use BeSimple\RosettaBundle\Translation\Scanner\Twig\Extension\ExtensionInterface;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
abstract class AbstractTwigScanner
{
    /**
     * @var array
     */
    private $extensions;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * Constructor.
     *
     * @param \Twig_Environment $twig
     */
    public function __construct(\Twig_Environment $twig = null)
    {
        $this->twig = $twig ?: new \Twig_Environment();
        $this->twig->setLoader(new \Twig_Loader_String());
        $this->twig->disableStrictVariables();

        $this->extensions = array();
    }

    /**
     * @param string $content The source code to scan
     * @param array  $context An array of variables
     * @return array
     */
    protected function parse($content, array $context = array())
    {
        $this->twig->setExtensions($this->extensions);
        $template = $this->twig->loadTemplate($content);

        ob_start();
        $template->display($context);
        ob_end_clean();

        $messages = array();
        foreach ($this->extensions as $extension) {
            $messages = array_merge($messages, $extension->getMessages());
        }

        return $messages;
    }

    /**
     * @param ExtensionInterface $extension An ExtensionInterface instance
     */
    protected function addExtension(ExtensionInterface $extension)
    {
        $this->extensions[] = $extension;
    }
}
