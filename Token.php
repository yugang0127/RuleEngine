<?php

namespace App\Services\RuleEngine;

interface Token
{
    /**
     * 获取Token的类型
     * @return int
     */
    public function  getType();

    /**
     * 获取Token的文本值
     * @return string
     */
    public function getText();
}
