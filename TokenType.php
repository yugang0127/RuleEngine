<?php


namespace App\Services\RuleEngine;


class TokenType
{

    const Plus = 1; // +
    const Minus = 2; // -
    const Multiply = 3; // *
    const Divide = 4; // /

    const SemiColon = 10; // ;
    const LeftParen = 11; // (
    const RightParen = 12; // )
    const Assignment = 13; // =

    const GE = 21; // >
    const GT = 22; // >=
    const EQ = 23; // ==
    const LE = 24; // <
    const LT = 25; // <=

    const IntLiteral = 31;
    const DecimalLiteral = 32;
    const Variable = 41;

}