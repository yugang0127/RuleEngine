<?php


namespace App\Services\RuleEngine;


class ASTNodeType
{
    const Programm = 'Programm';           // 程序入口，根节点
    const ExpressionStmt = 'ExpressionStmt';     // 表达式语句，即表达式后面跟个分号

    const Primary = 'Primary';            // 基础表达式
    const Multiplicative = 'Multiplicative';     // 乘法表达式
    const Additive = 'Additive';           // 加法表达式

    const Variable = 'Variable';           // 变量
    const IntLiteral = 'IntLiteral';         // 整形字面量
    const DecimalLiteral = 'DecimalLiteral';     // 实数字面量
}