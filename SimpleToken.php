<?php


namespace App\Services\RuleEngine;


class SimpleToken implements Token
{
    public $type;
    public $text;

    /**
     * 获取Token的类型
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * 获取Token的文本值
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }
}