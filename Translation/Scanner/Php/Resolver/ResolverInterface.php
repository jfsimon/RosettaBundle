<?php

namespace BeSimple\RosettaBundle\Translation\Scanner\Php\Resolver;

use BeSimple\RosettaBundle\Translation\Scanner\Php\Parser\TokenStack;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
interface ResolverInterface
{
    /**
     * Replaces some tokens by resolved tokens.
     *
     * @param TokenStack $tokens A TokenStack instance
     */
    function resolver(TokenStack $tokens);
}
