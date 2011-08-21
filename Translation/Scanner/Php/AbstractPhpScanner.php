<?php

namespace BeSimple\RosettaBundle\Translation\Scanner\Php;

use BeSimple\RosettaBundle\Translation\Scanner\Php\Statement\StringStatement;
use BeSimple\RosettaBundle\Translation\Scanner\Php\Statement\ArrayStatement;
use BeSimple\RosettaBundle\Translation\Scanner\Php\Statement\FunctionCallStatement;
use BeSimple\RosettaBundle\Translation\Scanner\Php\Parser\TokenStack;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
abstract class AbstractPhpScanner
{
    /**
     * Parses a string from given tokens.
     *
     * @throws ScannerException
     *
     * @param TokenStack $argument A TokenStack instance
     *
     * @return string The string
     */
    protected function parseString(TokenStack $argument)
    {
        $statement = new StringStatement($argument);

        return $statement->getString();
    }

    /**
     * Parses ArrayStatement's string keys from given tokens.
     *
     * @throws ScannerException
     *
     * @param TokenStack $argument A TokenStack instance
     *
     * @return array|null An array of string or Null if none found
     */
    protected function parseArrayStringKeys(TokenStack $argument)
    {
        $statement = new ArrayStatement($argument);
        $keys      = array();

        foreach ($statement->getKeys() as $key) {
            $keyStatement = new StringStatement($key);
            $keys[] = $keyStatement->getString();
        }

        return count($keys) ? $keys : null;
    }
}
