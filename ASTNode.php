<?php


namespace App\Services\RuleEngine;


interface ASTNode
{
    /**
     * 获取父节点
     * @return ASTNode
     */
    public function getParent();

    /**
     * 获取子节点
     * @return mixed
     */
    public function getChildren();

    /**
     * 获取节点类型
     * @return ASTNodeType
     */
    public function getType();

    /**
     * 获取节点文本值
     * @return string
     */
    public function getText();
}