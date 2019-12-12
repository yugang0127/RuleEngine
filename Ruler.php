<?php


namespace App\Services\RuleEngine;


class Ruler
{

    public function initRule($version)
    {
        echo __DIR__ . "\r\n";
        $filePath = __DIR__ . '/../../../config/order_' . $version . '.drl';
        $lines = file($filePath);
        $index = 0;
        foreach ($lines as $line) {
            echo $index++ . ':' . $line . "\r\n";
        }
    }
}