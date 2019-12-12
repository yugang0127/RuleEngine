<?php


namespace App\Services\RuleEngine;


class SimpleTokenReader implements TokenReader
{
    private $tokens = [];
    private $pos = 0;

    public function __construct($tokens)
    {
        $this->tokens = $tokens;
    }

    /**
     * 返回Token流中下一个Token，并从流中取出
     * @return Token
     */
    public function read()
    {
        if ($this->pos < count($this->tokens)) {
            return $this->tokens[$this->pos++];
        }
        return null;
    }

    /**
     * 返回Token流中下一个Token，但不从流中取出
     * @return Token
     */
    public function peek()
    {
        if ($this->pos < count($this->tokens)) {
            return $this->tokens[$this->pos];
        }
        return null;
    }

    /**
     * Token流回退一步
     * @return void
     */
    public function unread()
    {
        if ($this->pos > 0) {
            $this->pos--;
        }
    }

    /**
     * 设置Token流当前位置
     * @return void
     */
    public function setPostion($position)
    {
        if ($position > 0 && $position < count($this->tokens)) {
            $this->pos = $position;
        }
    }

    /**
     * 获取Token流当前位置
     * @return int
     */
    public function getPosition()
    {
        return $this->pos;
    }
}