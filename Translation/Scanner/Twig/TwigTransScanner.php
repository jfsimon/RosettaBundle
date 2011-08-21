<?php

namespace BeSimple\RosettaBundle\Translation\Scanner\Twig;

use BeSimple\RosettaBundle\Translation\Scanner\ScannerInterface;
use BeSimple\RosettaBundle\Translation\Scanner\Twig\Extension\TransExtension;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class TwigTransScanner extends AbstractTwigScanner implements ScannerInterface
{
    /**
     * {@inheritdoc}
     */
    public function scan($content, array $context = array())
    {
        $this->addExtension(new TransExtension());

        return $this->parse($content, $context);
    }
}
