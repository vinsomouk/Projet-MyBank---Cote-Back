<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordHasher
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function hashUserPassword(User $user): void
    {
        if (null === $user->getPlainPassword()) {
            return;
        }

        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $user->getPlainPassword()
        );
        
        $user->setPassword($hashedPassword);
        $user->eraseCredentials();
    }
}