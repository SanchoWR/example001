<?php

namespace App\Util;


use App\Entity\User;

class PasswordUtil
{
    public static function hash(string $password): string
    {
        $salt = include dirname(__DIR__, 2) . '/config/salt.php';

        return md5($password . $salt);
    }

    public static function check(User $user, string $password): bool
    {
        return null !== $user->getPassword() && $user->getPassword() === self::hash($password);
    }
}
