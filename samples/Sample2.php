<?php

class User
{
    public static function create(): User
    {
        return new User();
    }
}

User::create();
User::make();                   // ← 存在しない静的メソッド
