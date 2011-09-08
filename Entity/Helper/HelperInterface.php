<?php

namespace BeSimple\RosettaBundle\Entity\Helper;

/**
 * Interface message texts helpers.
 *
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
interface HelperInterface
{
    /**
     * Returns a hash of given text.
     *
     * @param $text A text to hash
     *
     * @return string The hash
     */
    function hash($text);

    /**
     * Returns true if given text looks like a translation key.
     *
     * @param $text A text to translate
     *
     * @return bool Is given text a translation key
     */
    function isKey($text);

    /**
     * Return true if given text looks like message choices.
     *
     * @param $text A text to translate
     *
     * @return bool Is given text choices
     */
    function isChoice($text);
}
