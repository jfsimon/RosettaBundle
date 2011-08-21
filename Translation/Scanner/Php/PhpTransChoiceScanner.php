<?php

namespace BeSimple\RosettaBundle\Translation\Scanner\Php;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class PhpTransChoiceScanner extends PhpTransScanner
{
    protected $methodName      = 'transChoice';
    protected $parametersIndex = 2;
    protected $domainIndex     = 3;
    protected $isChoice        = true;
}
