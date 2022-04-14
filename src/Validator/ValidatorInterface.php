<?php

namespace App\Validator;


use App\Entity\User;

interface ValidatorInterface
{
    public function validate(User $user);
}
