<?php

class User
{
    public function rename(string $name): void
    {
        // ...
    }
}

$user = new User();
$user->rename('Hanako');
$user->remove();
