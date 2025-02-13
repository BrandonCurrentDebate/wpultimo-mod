<?php

declare (strict_types=1);
namespace WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\TheSeer\Tokenizer;

class TokenCollection implements \ArrayAccess, \Iterator, \Countable
{
    /** @var Token[] */
    private $tokens = [];
    /** @var int */
    private $pos;
    public function addToken(\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\TheSeer\Tokenizer\Token $token) : void
    {
        $this->tokens[] = $token;
    }
    public function current() : \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\TheSeer\Tokenizer\Token
    {
        return \current($this->tokens);
    }
    public function key() : int
    {
        return \key($this->tokens);
    }
    public function next() : void
    {
        \next($this->tokens);
        $this->pos++;
    }
    public function valid() : bool
    {
        return $this->count() > $this->pos;
    }
    public function rewind() : void
    {
        \reset($this->tokens);
        $this->pos = 0;
    }
    public function count() : int
    {
        return \count($this->tokens);
    }
    public function offsetExists($offset) : bool
    {
        return isset($this->tokens[$offset]);
    }
    /**
     * @throws TokenCollectionException
     */
    public function offsetGet($offset) : \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\TheSeer\Tokenizer\Token
    {
        if (!$this->offsetExists($offset)) {
            throw new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\TheSeer\Tokenizer\TokenCollectionException(\sprintf('No Token at offest %s', $offset));
        }
        return $this->tokens[$offset];
    }
    /**
     * @param Token $value
     *
     * @throws TokenCollectionException
     */
    public function offsetSet($offset, $value) : void
    {
        if (!\is_int($offset)) {
            $type = \gettype($offset);
            throw new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\TheSeer\Tokenizer\TokenCollectionException(\sprintf('Offset must be of type integer, %s given', $type === 'object' ? \get_class($value) : $type));
        }
        if (!$value instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\TheSeer\Tokenizer\Token) {
            $type = \gettype($value);
            throw new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\TheSeer\Tokenizer\TokenCollectionException(\sprintf('Value must be of type %s, %s given', \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\TheSeer\Tokenizer\Token::class, $type === 'object' ? \get_class($value) : $type));
        }
        $this->tokens[$offset] = $value;
    }
    public function offsetUnset($offset) : void
    {
        unset($this->tokens[$offset]);
    }
}
