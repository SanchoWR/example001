<?php

namespace App\Validator;


use App\Database\DbInterface;
use App\Entity\User;


class Validator implements ValidatorInterface
{
    private $db;

    public function __construct(DbInterface $db)
    {
        $this->db = $db;
    }

    public function validate(User $newUser): ?array
    {
        $errors = [];

        $username = $this->db->read($newUser->getUsername());
        if (null !== $username) {
            $errors[User::USERNAME] = 'This user already exists';
            return $errors;
        }

        $username = $this->validateUsername($newUser);
        if (null !== $username) {
            $errors[User::USERNAME] = $username;
        }

        $password = $this->validatePassword($newUser);
        if (null !== $password) {
            $errors[User::PASSWORD] = $password;
        }

        $confirmPassword = $this->validateConfirmPassword($newUser);
        if (null !== $confirmPassword) {
            $errors[User::CONFIRM_PASSWORD] = $confirmPassword;
        }

        $email = $this->validateEmail($newUser);
        if (null !== $email) {
            $errors[User::EMAIL] = $email;
        }

        return empty($errors) ? null : $errors;
    }

    private function validateEmail(User $newUser): ?string
    {
        if (empty($newUser->getEmail())) {
            return 'Please enter your email';
        }

        $email = $this->db->findOneBy([User::EMAIL => $newUser->getEmail()]);

        if ($email) {
            return 'This email already exists';
        }

        if (!preg_match('/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i', $newUser->getEmail())) {
            return 'Not valid email';
        }

        return null;
    }

    private function validateConfirmPassword(User $newUser): ?string
    {
        if (empty($newUser->getConfirmPassword())) {
            return 'Please enter your confirm password';
        }

        return $newUser->getPassword() !== $newUser->getConfirmPassword() ? "Confirm password doesn't match" : null;
    }

    private function validatePassword(User $newUser): ?string
    {
        if (empty($newUser->getPassword())) {
            return 'Please enter your password';
        }

        $message = $this->minLength(6, $newUser->getPassword());
        if (null !== $message) {
            return $message;
        }

        if (!(1 === preg_match('/^(?:\d+[a-z]|[a-z]+\d)[a-z\d]*$/i', $newUser->getPassword()))) {
            return 'Letters and numbers are required';
        }

        return null;
    }

    private function minLength(int $value, string $str): ?string
    {
        if (strlen($str) < $value) {
            return sprintf('Minimum %d characters', $value);
        }

        return null;
    }

    private function validateUsername(User $newUser): ?string
    {
        if (empty($newUser->getUsername())) {
            return 'Please enter your username';
        }

        if (1 !== preg_match('/^\w+$/i', $newUser->getUsername())) {
            return 'Not valid username';
        }

        return $this->minLength(6, $newUser->getUsername());
    }
}
