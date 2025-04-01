<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api')]
class ApiController extends AbstractController
{
    #[Route('/transactions', name: 'api_transactions', methods: ['GET'])]
    public function getTransactions(EntityManagerInterface $em): JsonResponse
    {
        $transactions = $em->getRepository(Transaction::class)->findAll();
        return $this->json($transactions);
    }

    #[Route('/register', name: 'api_register', methods: ['POST'])]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $em
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        
        $user = new User();
        $user->setEmail($data['email']);
        $user->setName($data['name']);
        $user->setPassword(
            $passwordHasher->hashPassword($user, $data['password'])
        );
        
        $em->persist($user);
        $em->flush();
        
        return $this->json(['status' => 'user created'], 201);
    }
}