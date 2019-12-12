<?php


namespace App\Services\RuleEngine;


class SimpleASTNode implements ASTNode
{
    private $parent;
    private $children = [];
    private $nodeType;
    private $text;

    public function __construct($nodeType, String $text)
    {
        $this->nodeType = $nodeType;
        $this->text = $text;
    }

    /**
     * 获取父节点
     * @return ASTNode
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * 获取子节点
     * @return mixed
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * 获取节点类型
     * @return ASTNodeType
     */
    public function getType()
    {
        return $this->nodeType;
    }

    /**
     * 获取节点文本值
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    public function addChild(SimpleASTNode $child)
    {
        $child->parent = $this;
        array_push($this->children, $child);
    }
}