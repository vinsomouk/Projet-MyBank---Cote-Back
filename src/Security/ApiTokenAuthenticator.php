<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class ApiTokenAuthenticator extends AbstractAuthenticator
{
    private UserProviderInterface $userProvider;

    public function __construct(UserProviderInterface $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    // Cette méthode vérifie si la requête contient un token
    public function supports(Request $request): ?bool
    {
        return $request->headers->has('Authorization');
    }

    // Authentifie l'utilisateur via le token
    public function authenticate(Request $request): Passport
    {
        $authorizationHeader = $request->headers->get('Authorization');

        if (!$authorizationHeader || !str_starts_with($authorizationHeader, 'Bearer ')) {
            throw new AuthenticationException('No Bearer token found');
        }

        $token = substr($authorizationHeader, 7); // retirer "Bearer "

        return new SelfValidatingPassport(
            new UserBadge($token, function ($token) {
                // Ici on charge l'utilisateur avec le token (par exemple depuis la BDD)
                $user = $this->userProvider->loadUserByIdentifier($token);

                if (!$user instanceof UserInterface) {
                    throw new AuthenticationException('Invalid API Token');
                }

                return $user;
            })
        );
    }

    // En cas de succès, on continue la requête normalement
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    // En cas d'échec, on renvoie une réponse JSON 401
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new Response(
            json_encode(['error' => 'Authentication Failed: ' . $exception->getMessage()]),
            Response::HTTP_UNAUTHORIZED,
            ['Content-Type' => 'application/json']
        );
    }
}
