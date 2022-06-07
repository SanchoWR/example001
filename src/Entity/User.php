<?php

namespace App\Entity;


class User
{
    const USERNAME = 'username';
    const PASSWORD = 'password';
    const CONFIRM_PASSWORD = 'confirm_password';
    const EMAIL = 'email';
    const NAME = 'name';

    private $username;
    private $password;
    private $confirmPassword;
    private $email;
    private $name;

    public function __construct(string $username = '', string $password = '', string $email = '', string $name = '')
    {
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->name = $name;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getConfirmPassword(): string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(string $confirmPassword): void
    {
        $this->confirmPassword = $confirmPassword;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public static function createFromArray(array $data): self
    {
        return new static($data[self::USERNAME], $data[self::PASSWORD], $data[self::EMAIL], $data[self::NAME]);
    }

    public function toArray(): array
    {
        return [
            self::USERNAME => $this->username,
            self::PASSWORD => $this->password,
            self::EMAIL => $this->email,
            self::NAME => $this->name
        ];
    }
}
