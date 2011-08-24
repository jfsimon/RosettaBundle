<?php

namespace BeSimple\RosettaBundle\Translation\ParametersGuesser;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class RegexpParametersGuesser extends AbstractParametersGuesser implements ParametersGuesserInterface
{
    const REGEXP_PATTERN = '/^\\/.*\\/[gi]*$/i';

    /**
     * @var array
     */
    protected $regexps;

    /**
     * Constructor.
     *
     * @param array $regexps An array of regexps
     */
    public function __construct(array $regexps = array())
    {
        foreach ($regexps as $regexp) {
            if (!preg_match(self::REGEXP_PATTERN, $regexp)) {
                throw new \InvalidArgumentException(sprintf('Pattern "%s" does not match "%s".', $regexp, self::REGEXP_PATTERN));
            }
        }

        $this->regexps = $regexps;
    }

    /**
     * {@inheritdoc}
     */
    public function guess($text)
    {
        $parameters = array();

        foreach ($this->regexps as $regexp) {
            $matches = array();

            if (preg_match_all($regexp, $text, $matches, PREG_PATTERN_ORDER)) {
                $parameters = array_merge($parameters, $matches[0]);
            }
        }

        return $parameters;
    }
}
