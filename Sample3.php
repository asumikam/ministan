<?php

class User
{
    public function rename(string $name): void
    {
    }

    public function reset(): void
    {
        $this->rename('Taro');
        $this->remove();        // ← 存在しないメソッド
    }
}
