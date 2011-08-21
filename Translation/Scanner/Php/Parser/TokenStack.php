<?php

namespace BeSimple\RosettaBundle\Translation\Scanner\Php\Parser;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class TokenStack
{
    /**
     * @var array
     */
    private $tokens;

    /**
     * @var array
     */
    private $ignore;

    /**
     * @var array
     */
    private $delimiters;

    /**
     * @var int
     */
    private $cursor;

    /**
     * Constructor.
     *
     * @param Token[] $tokens
     * @param array $ignore
     * @param array $delimiters
     */
    public function __construct(array $tokens = array(), array $ignore = array(T_WHITESPACE, T_BAD_CHARACTER, T_COMMENT, T_DOC_COMMENT), array $delimiters = array('(' => ')', '[' => ']', '{' => '}'))
    {
        $this->ignore     = $ignore;
        $this->tokens     = $tokens;
        $this->delimiters = $delimiters;
        $this->cursor     = 0;
    }

    /**
     * Gets token for given index.
     * If index is negative, start by the end.
     *
     * @param int $index
     * @return Token|null
     */
    public function get($index)
    {
        if ($index < 0) {
            $index = count($this->tokens) + $index;
        }

        return isset($this->tokens[$index]) ? $this->tokens[$index] : null;
    }

    /**
     * Sets token by index.
     *
     * @param int $index
     * @param Token|array|string $token
     * @return TokenStack
     */
    public function set($index, $token)
    {
        $this->tokens[$index] = $token instanceof Token ? $token : new Token($token);

        return $this;
    }

    /**
     * Returns current cursor position.
     *
     * @return int
     */
    public function cursor()
    {
        return $this->cursor - 1;
    }

    /**
     * Reset cursor position.
     *
     * @return TokenStack
     */
    public function rewind()
    {
        $this->cursor = 0;

        return $this;
    }

    /**
     * Fetch next token.
     *
     * @return Token|null A Token instance, null if there is no more token
     */
    public function next()
    {
        if (isset($this->tokens[$this->cursor])) {
            return $this->tokens[$this->cursor ++];
        }

        return null;
    }

    /**
     * Append a token to the stack.
     *
     * @param array|string|Token $token
     *
     * @return TokenStack
     */
    public function push($token)
    {
        if (!$token instanceof Token) {
            $token = new Token($token);
        }

        if (!in_array($token->getType(), $this->ignore)) {
            $this->tokens[] = $token;
        }

        return $this;
    }

    /**
     * Returns number of tokens.
     *
     * @return int
     */
    public function count()
    {
        return count($this->tokens);
    }

    /**
     * Shift tokens from the stack.
     *
     * @param int $length
     *
     * @return TokenStack
     */
    public function shift($length = 1)
    {
        $shiftedTokens = array_slice($this->tokens, 0, $length);
        $this->tokens  = array_slice($this->tokens, $length);

        return new TokenStack($shiftedTokens, $this->ignore, $this->delimiters);
    }

    /**
     * Pops tokens from the stack.
     *
     * @param int $length
     *
     * @return TokenStack
     */
    public function pop($length = 1)
    {
        $newCount     = count($this->tokens) - $length;
        $poppedTokens = array_slice($this->tokens, $newCount, $length);
        $this->tokens = array_slice($this->tokens, 0, $newCount);

        return new TokenStack($poppedTokens, $this->ignore, $this->delimiters);
    }

    /**
     * Extract next tokens from the stack until given token is found.
     *
     * @param Token $until
     *
     * @return TokenStack
     */
    public function extract(Token $until, $keepLast = false)
    {
        $index = $this->cursor();
        $open  = array();

        while (isset($this->tokens[$index])) {
            if (isset($this->delimiters[$this->tokens[$index]->getContent()])) {
                array_unshift($open, $this->tokens[$index]->getContent());
            }

            if (count($open) && $this->delimiters[$open[0]] === $this->tokens[$index]->getContent()) {
                array_shift($open);
            }

            $matchType    = is_null($until->getType()) || $until->getType() === $this->tokens[$index]->getType();
            $matchContent = is_null($until->getContent()) || $until->getContent() === $this->tokens[$index]->getContent();

            if (count($open) === 0 && $matchType && $matchContent) {
                break;
            }

            $index ++;
        }

        $length = $keepLast ? $index - $this->cursor() + 1 : $index - $this->cursor();

        return new TokenStack(array_slice($this->tokens, $this->cursor(), $length), $this->ignore, $this->delimiters);
    }

    /**
     * Retrieve source code from tokens in the stack.
     *
     * @return string
     */
    public function source()
    {
        $contents = array();

        foreach ($this->tokens as $token)
        {
            $contents = $token->getContent();
        }

        return implode($contents, ' ');
    }
}
