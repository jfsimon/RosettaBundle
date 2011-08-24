<?php

namespace BeSimple\RosettaBundle\Translation\ParametersGuesser;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class ParametersGuesserWrapper implements ParametersGuesserInterface
{
    /**
     * @var array
     */
    private $guessers;

    /**
     * Constructor.
     *
     * @parameter array $guessers An array of guessers
     */
    public function __construct(array $guessers = array())
    {
        $this->guessers = array();

        foreach ($guessers as $guesser) {
            $this->add($guesser);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function guess($text)
    {
        $found = array();

        foreach ($this->guessers as $guesser) {
            $found = array_merge($found, $guesser->guess($text));
        }

        return array_unique($found);
    }

    /**
     * {@inheritdoc}
     */
    public function validate($text, array $parameters)
    {
        $found = array();

        foreach ($this->guessers as $guesser) {
            $found = array_merge($found, $guesser->validate($text, $parameters));
        }

        return array_unique($found);
    }

    /**
     * Adds a guesser.
     *
     * @param ParametersGuesserInterface $guesser A guesser instance
     *
     * @return ParametersGuesserWrapper This instance
     */
    public function add(ParametersGuesserInterface $guesser)
    {
        $this->guessers[] = $guesser;

        return $this;
    }

    /**
     * Clears all guessers.
     *
     * @return ParametersGuesserWrapper This instance
     */
    public function clear()
    {
        $this->guessers = array();

        return $this;
    }

    /**
     * Returns all guessers.
     *
     * @return array An array of guessers
     */
    public function all()
    {
        return $this->guessers;
    }
}
