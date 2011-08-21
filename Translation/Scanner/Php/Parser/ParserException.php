<?php

namespace BeSimple\RosettaBundle\Translation\Scanner\Php\Parser;

use BeSimple\RosettaBundle\Translation\Scanner\Exception\ScannerException;

/**
 * PHP source code parser exception.
 *
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class ParserException extends ScannerException
{
    /**
     * Constructor.
     *
     * @param string     $message A message
     * @param TokenStack $tokens  A TokenStack instance
     */
    public function __construct($message, TokenStack $tokens)
    {
        $tokens->rewind();
        while ($token = $tokens->next()) {
            if (!is_null($token->getLine())) {
                $this->line = $token->getLine();
                break;
            }
        }

        $this->message = sprintf('%s, got "%s"', $message, $tokens->source());
    }
}
