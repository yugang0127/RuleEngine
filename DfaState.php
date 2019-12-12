<?php


namespace App\Services\RuleEngine;


class DfaState
{

    const Initial = 0;       // 初始状态

    const Plus = 1;          // +
    const Minus = 2;         // -
    const Multiply = 3;      // *
    const Divide = 4;        // /

    const Variable = 10;     // 变量$开头
    const SemiColon = 11;    // ;
    const LeftParen = 12;    // (
    const RightParen = 13;   // )
    const Assignment = 14;   // =

    const IntLiteral = 21;   // int
    const DecimalLiteral = 22;   // int
}