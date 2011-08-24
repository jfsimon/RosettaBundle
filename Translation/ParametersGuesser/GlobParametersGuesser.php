<?php

namespace BeSimple\RosettaBundle\Translation\ParametersGuesser;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class GlobParametersGuesser extends RegexpParametersGuesser implements ParametersGuesserInterface
{
    const GLOB_PATTERN = '/^[^*]+\\*[^*]+$/i';

    /**
     * Constructor.
     *
     * @param array $globs An array of globs
     */
    public function __construct(array $globs = array())
    {
        foreach ($globs as $glob) {
            if (!preg_match(self::GLOB_PATTERN, $glob)) {
                throw new \InvalidArgumentException(sprintf('Pattern "%s" does not match "%s".', $glob, self::GLOB_PATTERN));
            }
        }

        $this->regexps = array();

        foreach ($globs as $glob) {
            $parts = explode('*', $glob);
            $this->regexps[] = sprintf('/%s\\s*[^%s]+\\s*%s/i', $parts[0], $parts[1], $parts[1]);
        }
    }
}
