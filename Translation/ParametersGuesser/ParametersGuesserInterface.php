<?php

namespace BeSimple\RosettaBundle\Translation\ParametersGuesser;

/**
 * Interface for parameters guesser classes
 *
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
interface ParametersGuesserInterface
{
    /**
     * Guesses parameters contained in a message or a translation.
     *
     * @param  $text Text containing (or not) parameters
     *
     * @return array An array of guessed parameters
     */
    function guess($text);

    /**
     * Validates guessed parameters against given parameters.
     *
     * @param string $text       Text containing (or not) parameters
     * @param array  $parameters An array of parameters
     *
     * @return array An array of validated parameters
     */
    function validate($text, array $parameters);
}
