<?php

namespace BeSimple\RosettaBundle\Translation\Scanner\Php\Parser;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class Token
{
    /**
     * @var string|null
     */
    private $type;

    /**
     * @var string
     */
    private $content;

    /**
     * @var int|null
     */
    private $line;

    /**
     * Constructor.
     *
     * @param array|string $token A token (as returned by token_get_all())
     */
    public function __construct($token)
    {
        if (!is_array($token)) {
            $token = array(null, $token, null);
        }

        $this->type    = $token[0];
        $this->content = $token[1];
        $this->line    = $token[2];
    }

    /**
     * @return null|string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return int|null
     */
    public function getLine()
    {
        return $this->line;
    }
}
