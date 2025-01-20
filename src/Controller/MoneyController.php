<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MoneyController extends AbstractController{
    #[Route('/money', name: 'app_money')]
    public function index(): Response
    {
        return $this->render('money/index.html.twig', [
            'controller_name' => 'MoneyController',
        ]);
    }
}
