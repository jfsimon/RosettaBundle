<?php

namespace BeSimple\RosettaBundle\Translation\ParametersGuesser;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
abstract class AbstractParametersGuesser
{
    /**
     * {@inheritdoc}
     */
    public function validate($text, array $parameters)
    {
        return array_intersect($parameters, $this->guess($text));
    }
}
