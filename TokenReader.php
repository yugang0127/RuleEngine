<?php

namespace App\Services\RuleEngine;

/**
 * 一个Token流，由Lexer生成。Parser可以从中获取数据
 */
interface TokenReader
{
    /**
     * 返回Token流中下一个Token，并从流中取出
     * @return Token
     */
    public function read();

    /**
     * 返回Token流中下一个Token，但不从流中取出
     * @return Token
     */
    public function peek();

    /**
     * Token流回退一步
     * @return mixed
     */
    public function unread();

    /**
     * 设置Token流当前位置
     * @return mixed
     */
    public function setPostion($position);

    /**
     * 获取Token流当前位置
     * @return mixed
     */
    public function getPosition();
    
}
