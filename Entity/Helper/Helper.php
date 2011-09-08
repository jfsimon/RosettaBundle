<?php

namespace BeSimple\RosettaBundle\Entity\Helper;

/**
 * Helper class for message texts.
 *
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class Helper implements HelperInterface
{
    /**
     * @var \Closure
     */
    private $hasher;

    /**
     * @var \Closure
     */
    private $keyGuesser;

    /**
     * @var \Closure
     */
    private $choiceGuesser;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->hasher = function($text) {
            // sha1 + md5 to avoid collisions
            return sha1($text).md5($text);
        };

        $this->keyGuesser = function($text) {
            // no white and at least one dot
            return !preg_match('/\s/', $text) && strpos($text, '.');
        };

        $this->choiceGuesser = function($text) {
            // just looks for a pipe
            return strpos($text, '|');
        };
    }

    /**
     * {@inheritdoc}
     */
    public function hash($text)
    {
        $closure = $this->hasher;

        return $closure($text);
    }

    /**
     * {@inheritdoc}
     */
    public function isKey($text)
    {
        $closure = $this->keyGuesser;

        return $closure($text);
    }

    /**
     * {@inheritdoc}
     */
    public function isChoice($text)
    {
        $closure = $this->choiceGuesser;

        return $closure($text);
    }

    /**
     * @param \Closure $choiceGuesser
     *
     * @return Helper
     */
    public function setChoiceGuesser(\Closure $choiceGuesser)
    {
        $this->choiceGuesser = $choiceGuesser;

        return $this;
    }

    /**
     * @return \Closure
     */
    public function getChoiceGuesser()
    {
        return $this->choiceGuesser;
    }

    /**
     * @param \Closure $hasher
     *
     * @return Helper
     */
    public function setHasher(\Closure $hasher)
    {
        $this->hasher = $hasher;

        return $this;
    }

    /**
     * @return \Closure
     */
    public function getHasher()
    {
        return $this->hasher;
    }

    /**
     * @param \Closure $keyGuesser
     *
     * @return Helper
     */
    public function setKeyGuesser(\Closure $keyGuesser)
    {
        $this->keyGuesser = $keyGuesser;

        return $this;
    }

    /**
     * @return \Closure
     */
    public function getKeyGuesser()
    {
        return $this->keyGuesser;
    }
}
