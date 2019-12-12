<?php


namespace App\Services\RuleEngine;


class SimpleCalculator
{
    private $context = [];

    public function __construct($context)
    {
        $this->context = $context;
    }

    public function test()
    {
        $this->context = ['price' => 100, 'carprice' => 101];
        $script = '10+($price+3)*5.1-$carprice;';
        $this->evaluate($script);
        $script = '2+3+4+5';
        $this->evaluate($script);
    }

    /**
     * 执行脚本，打印输出AST和求值过程
     * @param $script
     */
    public function evaluate($script)
    {
        try {
            $tree = $this->parse($script);
            $this->dumpAST($tree, "");
            $this->evaluateNode($tree, "");
        } catch (\Exception $e) {
            echo $e->getMessage() . "\r\n";
        }

    }

    public function parse(String $code)
    {
        $lexer = new SimpleLexer();
        $tokens = $lexer->tokenize($code);

        $rootNode = $this->prog($tokens);

        return $rootNode;
    }

    /**
     * 语法解析：根节点
     * @param TokenReader $tokens
     */
    private function prog(TokenReader $tokens)
    {
        $node = new SimpleASTNode(ASTNodeType::Programm, 'Calculator');
        $child = $this->additive($tokens);

        if ($child != null) {
            $node->addChild($child);
        }
        return $node;
    }

    /**
     * 对某个AST节点求值，并打印求值过程
     * @param ASTNode $node
     * @param String $indent
     */
    private function evaluateNode(ASTNode $node, String $indent)
    {
        $result = 0;
        echo $indent . "Calculating: " . $node->getType() . "\r\n";
        switch ($node->getType()) {
            case ASTNodeType::Programm :
                foreach ($node->getChildren() as $child) {
                    $result = $this->evaluateNode($child, $indent . "\t");
                }
                break;
            case ASTNodeType::Additive :
                $child1 = $node->getChildren()[0];
                $value1 = $this->evaluateNode($child1, $indent . "\t");
                $child2 = $node->getChildren()[1];
                $value2 = $this->evaluateNode($child2, $indent . "\t");
                if ($node->getText() == '+') {
                    $result = $value1 + $value2;
                } else {
                    $result = $value1 - $value2;
                }
                break;
            case ASTNodeType::Multiplicative :
                $child1 = $node->getChildren()[0];
                $value1 = $this->evaluateNode($child1, $indent . "\t");
                $child2 = $node->getChildren()[1];
                $value2 = $this->evaluateNode($child2, $indent . "\t");
                if ($node->getText() == '*') {
                    $result = $value1 * $value2;
                } else {
                    $result = $value1 / $value2;
                }
                break;
            case ASTNodeType::IntLiteral :
                $result = intval($node->getText());
                break;
            case ASTNodeType::DecimalLiteral :
                $result = floatval($node->getText());
                break;
            case ASTNodeType::Variable :
                $name = substr($node->getText(), 1);
                if (isset($this->context[$name])) {
                    $result = $this->context[$name];
                } else {
                    throw new \Exception("Variable ${$name} not defined.");
                }
                break;
        }
        echo $indent . "Result: " . $result . "\r\n";

        return $result;
    }

    /**
     * 语法解析：加法表达式
     * @param TokenReader $tokens
     */
    private function additive(TokenReader $tokens)
    {
        $child1 = $this->multiplicative($tokens);
        $node = $child1;

        $token = $tokens->peek();
        if ($child1 != null && $token != null) {
            if ($token->getType() == TokenType::Plus || $token->getType() == TokenType::Minus) {
                $token = $tokens->read();
                $child2 = $this->additive($tokens);
                if ($child2 != null) {
                    $node = new SimpleASTNode(ASTNodeType::Additive, $token->getText());
                    $node->addChild($child1);
                    $node->addChild($child2);
                } else {
                    throw new \Exception('invalid additive expression, expecting the right part.');
                }
            }
        }

        return $node;
    }

    /**
     * 语法解析：乘法表达式
     * @param TokenReader $tokens
     */
    private function multiplicative(TokenReader $tokens)
    {
        $child1 = $this->primary($tokens);
        $node = $child1;

        $token = $tokens->peek();
        if ($child1 != null && $token != null) {
            if ($token->getType() == TokenType::Multiply || $token->getType() == TokenType::Divide) {
                $token = $tokens->read();
                $child2 = $this->primary($tokens);
                if ($child2 != null) {
                    $node = new SimpleASTNode(ASTNodeType::Multiplicative, $token->getText());
                    $node->addChild($child1);
                    $node->addChild($child2);
                } else {
                    throw new \Exception("invalid multiplicative expression, expecting the right part.");
                }
            }
        }

        return $node;
    }

    /**
     * 语法解析：基础表达式
     * @param TokenReader $tokens
     */
    private function primary(TokenReader $tokens)
    {
        $node = null;
        $token = $tokens->peek();
        if ($token != null) {
            if ($token->getType() == TokenType::Variable) {
                $token = $tokens->read();
                $node = new SimpleASTNode(ASTNodeType::Variable, $token->getText());
            } elseif ($token->getType() == TokenType::IntLiteral) {
                $token = $tokens->read();
                $node = new SimpleASTNode(ASTNodeType::IntLiteral, $token->getText());
            } elseif ($token->getType() == TokenType::DecimalLiteral) {
                $token = $tokens->read();
                $node = new SimpleASTNode(ASTNodeType::DecimalLiteral, $token->getText());
            } elseif ($token->getType() == TokenType::LeftParen) {
                $tokens->read();
                $node = $this->additive($tokens);
                if ($node != null) {
                    $token = $tokens->peek();
                    if ($token != null && $token->getType() == TokenType::RightParen) {
                        $tokens->read();
                    } else {
                        throw new \Exception("expecting right parenthesis");
                    }
                } else {
                    throw new \Exception("expecting an additive expression inside parenthesis");
                }
            }
        }

        return $node;
    }

    private function dumpAST($node, $indent)
    {
        echo $indent . $node->getType() . ' ' . $node->getText() . "\r\n";
        foreach ($node->getChildren() as $child) {
            $this->dumpAST($child, $indent . "\t");
        }
    }
}