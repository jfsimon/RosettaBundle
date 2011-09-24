<?php

namespace BeSimple\RosettaBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use BeSimple\RosettaBundle\Command\Helper\FormatterHelper;
use BeSimple\RosettaBundle\Command\Helper\DialogHelper;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
abstract class AbstractCommand extends ContainerAwareCommand
{
    protected $headerTitle   = null;
    protected $headerMessage = null;

    /**
     * @param string $title
     * @param string $message
     *
     * @return AbstractCommand This instance
     */
    protected function setHeader($title, $message = null)
    {
        $this->headerTitle   = $title;
        $this->headerMessage = $message;

        return $this;
    }

    /**
     * @param OutputInterface $output
     */
    protected function displayHeader(OutputInterface $output)
    {
        if ($this->headerTitle) {
            $output->write($this
                ->getFormatterHelper()
                ->formatHeader($this->headerTitle, $this->headerMessage)
            );
        }
    }

    /**
     * @return FormatterHelper
     */
    protected function getFormatterHelper()
    {
        $formatter = $this->getHelperSet()->get('formatter');
        if (!$formatter || get_class($formatter) !== 'BeSimple\RosettaBundle\Command\Helper\FormatterHelper') {
            $this->getHelperSet()->set($formatter = new FormatterHelper());
        }

        return $formatter;
    }

    /**
     * @return DialogHelper
     */
    protected function getDialogHelper()
    {
        $dialog = $this->getHelperSet()->get('dialog');
        if (!$dialog || get_class($dialog) !== 'BeSimple\RosettaBundle\Command\Helper\DialogHelper') {
            $formatter = $this->getFormatterHelper();
            $this->getHelperSet()->set($dialog = new DialogHelper($formatter));
        }

        return $dialog;
    }
}
