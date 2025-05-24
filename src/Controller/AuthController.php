<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AuthController extends AbstractController
{
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
public function login(
    Request $request,
    EntityManagerInterface $em,
    UserPasswordHasherInterface $passwordHasher
): JsonResponse {
    $data = json_decode($request->getContent(), true);
    
    // Debug: Log les données reçues
    error_log(print_r($data, true));

    if (!isset($data['email']) || !isset($data['password'])) {
        return $this->json(['error' => 'Email and password required'], 400);
    }

    $user = $em->getRepository(User::class)->findOneBy(['email' => $data['email']]);

    if (!$user) {
        return $this->json(['error' => 'User not found'], 401);
    }

    if (!$passwordHasher->isPasswordValid($user, $data['password'])) {
        return $this->json(['error' => 'Invalid password'], 401);
    }

    return $this->json([
        'token' => 'generated-jwt-token', // À remplacer par un vrai token si vous utilisez JWT
        'user' => [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'name' => $user->getName()
        ]
    ]);
}}