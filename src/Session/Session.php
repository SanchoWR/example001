<?php

namespace App\Session;


class Session
{
    public static function create()
    {
        self::start();

        return new static();
    }

    public static function start(): void
    {
        session_start();
    }

    public function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public function set(string $key, string $value): self
    {
        $_SESSION[$key] = $value;

        return $this;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $_SESSION);
    }

    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public function destroy(): void
    {
        session_destroy();
    }
}