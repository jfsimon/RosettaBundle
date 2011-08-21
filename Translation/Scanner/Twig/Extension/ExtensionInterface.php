<?php

namespace BeSimple\RosettaBundle\Translation\Scanner\Twig\Extension;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
interface ExtensionInterface extends \Twig_ExtensionInterface
{
    /**
     * Returns found messages.
     *
     * @return array
     */
    function getMessages();
}
