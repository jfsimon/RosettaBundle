<?php

namespace BeSimple\RosettaBundle\Translation\Scanner\Twig;

use BeSimple\RosettaBundle\Translation\Scanner\ScannerInterface;
use BeSimple\RosettaBundle\Translation\Scanner\Twig\Extension\TransChoiceExtension;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class TwigTransChoiceScanner extends AbstractTwigScanner implements ScannerInterface
{
    /**
     * {@inheritdoc}
     */
    public function scan($content, array $context = array())
    {
        $this->addExtension(new TransChoiceExtension());

        return $this->parse($content, $context);
    }
}
