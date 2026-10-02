<?php

function greet(string $name): string
{
    return "Hello, {$name}";
}

greet('Taro');
gret('Taro');                   // ← 存在しない関数
