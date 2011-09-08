<?php

namespace BeSimple\RosettaBundle\Translation\Dumper;

use BeSimple\RosettaBundle\Entity\Helper\HelperInterface;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
abstract class AbstractDumper
{
    /**
     * @var MessageHelper
     */
    protected $helper;

    /**
     * Constructor.
     *
     * @param HelperInterface $helper A HelperInterface instance
     */
    public function __construct(HelperInterface $helper)
    {
        $this->helper = $helper;
    }

    /**
     * Expands an array of translations
     *
     * The scheme used is:
     *   'key.key2.key3' => 'value'
     * Becomes:
     *   'key' => array('key2' => array('key3' => 'value'))
     *
     * This function takes an array by reference and will modify it
     *
     * @param array $messages
     */
    protected function expand(array &$messages)
    {
        foreach ($messages as $key => $message) {
            if ($this->helper->isKey($key)) {
                $subnode = &$messages;

                foreach (explode('.', $key) as $subkey) {
                    $subnode[$subkey] = array();
                    $subnode = &$subnode[$subkey];
                }

                $subnode = $message;

                unset($messages[$key]);
            }
        }
    }
}
