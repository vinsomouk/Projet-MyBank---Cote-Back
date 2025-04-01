<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\PasswordHasher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AuthController extends AbstractController
{
    #[Route('/register', name: 'app_register', methods: ['POST'])]
    public function register(
        Request $request,
        PasswordHasher $passwordHasher,
        EntityManagerInterface $em,
        ValidatorInterface $validator
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $user = new User();
        $user->setEmail($data['email'] ?? '');
        $user->setName($data['name'] ?? '');
        $user->setPlainPassword($data['password'] ?? '');

        // Validation
        $errors = $validator->validate($user, null, ['registration']);
        if (count($errors) > 0) {
            return $this->json($errors, 400);
        }

        // Hashage du mot de passe
        $passwordHasher->hashUserPassword($user);

        // Persistance
        $em->persist($user);
        $em->flush();

        return $this->json([
            'status' => 'success',
            'message' => 'User registered successfully'
        ], 201);
    }
}