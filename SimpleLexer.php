<?php


namespace App\Services\RuleEngine;

/**
 * 一个简单的手写词法分析器
 */
class SimpleLexer
{
    private $tokenText; // 临时保存Token的文本
    private $tokens;    // 保存解析出来的Token
    private $token;     // 当前正在解析的Token

    public function test()
    {
        $code = '($pricehalf +$carloan)*11.1=$carprice;';
        $this->tokenize($code);
        $code = '$pricehalf * (8 - 5) = $carprice;';
        $this->tokenize($code);
    }

    /**
     * 解析字符串，形成Token
     * 这是一个有限状态自动机，在不同状态间迁移
     * @param $code 要解析的代码
     */
    public function tokenize(String $code)
    {
        echo "tokenize： {$code}\r\n";
        $this->tokenText = '';
        $this->tokens = [];
        $this->token = new SimpleToken();
        $state = DfaState::Initial;
        $ch = 0;

        $charArr = str_split($code);
        for ($i = 0; $i < count($charArr); $i++) {
            $ch = $charArr[$i];
            switch ($state) {
                case DfaState::Initial :
                    $state = $this->initToken($ch);
                    break;
                case DfaState::Variable :
                    if ($this->isAlpha($ch) || $this->isDigit($ch)) {
                        $this->tokenText .= $ch;
                    } else {
                        $state = $this->initToken($ch);
                    }
                    break;
                case DfaState::Assignment :
                case DfaState::Plus :
                case DfaState::Minus :
                case DfaState::Multiply :
                case DfaState::Divide :
                case DfaState::LeftParen :
                case DfaState::RightParen :
                case DfaState::SemiColon :
                    $state = $this->initToken($ch);
                    break;
                case DfaState::IntLiteral :
                    if ($this->isDigit($ch)) {
                        $this->tokenText .= $ch;
                    } elseif($ch == '.') {
                        $state = DfaState::DecimalLiteral;
                        $this->token->type = TokenType::DecimalLiteral;
                        $this->tokenText .= $ch;
                    } else {
                        $state = $this->initToken($ch);
                    }
                    break;
                case DfaState::DecimalLiteral :
                    if ($this->isDigit($ch)) {
                        $this->tokenText .= $ch;
                    } else {
                        $state = $this->initToken($ch);
                    }
                    break;

                default :
            }

        }
        if (strlen($this->tokenText) > 0) {
            $this->initToken($ch);
        }

        echo "tokens:\r\n";
        var_dump($this->tokens);

        return new SimpleTokenReader($this->tokens);
    }

    /**
     *
     * @param $ch
     */
    private function initToken($ch)
    {
        if (strlen($this->tokenText) > 0) {
            $this->token->text = $this->tokenText;
            array_push($this->tokens, $this->token);

            $this->tokenText = '';
            $this->token = new SimpleToken();
        }

        $newState = DfaState::Initial;
        if ($ch == '$' || $this->isAlpha($ch)) {
            $newState = DfaState::Variable;
            $this->token->type = TokenType::Variable;
            $this->tokenText .= $ch;
        } elseif ($this->isDigit($ch)) {
            $newState = DfaState::IntLiteral;
            $this->token->type = TokenType::IntLiteral;
            $this->tokenText .= $ch;
        } elseif ($ch == '(') {
           $newState = DfaState::LeftParen;
           $this->token->type = TokenType::LeftParen;
           $this->tokenText .= $ch;
        } elseif ($ch == ')') {
            $newState = DfaState::RightParen;
            $this->token->type = TokenType::RightParen;
            $this->tokenText .= $ch;
        } elseif ($ch == '+') {
            $newState = DfaState::Plus;
            $this->token->type = TokenType::Plus;
            $this->tokenText .= $ch;
        } elseif ($ch == '-') {
            $newState = DfaState::Minus;
            $this->token->type = TokenType::Minus;
            $this->tokenText .= $ch;
        } elseif ($ch == '*') {
            $newState = DfaState::Multiply;
            $this->token->type = TokenType::Multiply;
            $this->tokenText .= $ch;
        } elseif ($ch == '/') {
            $newState = DfaState::Divide;
            $this->token->type = TokenType::Divide;
            $this->tokenText .= $ch;
        } elseif ($ch == '=') {
            $newState = DfaState::Assignment;
            $this->token->type = TokenType::Assignment;
            $this->tokenText .= $ch;
        } elseif ($ch == ';') {
            $newState = DfaState::SemiColon;
            $this->token->type = TokenType::SemiColon;
            $this->tokenText .= $ch;
        }

        return $newState;
    }

    private function isAlpha($ch)
    {
        return $ch >= 'a' && $ch <= 'z' || $ch >= 'A' && $ch <= 'Z';
    }

    private function isDigit($ch)
    {
        return $ch >= '0' && $ch <= '9';
    }

    private function isBlank($ch)
    {
        return $ch == ' ' || $ch == '\t' || $ch == '\n';
    }
}

