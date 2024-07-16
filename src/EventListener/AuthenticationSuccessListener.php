<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AuthenticationSuccessListener
{
    private $httpClient;
    private $requestStack;
    private JWTTokenManagerInterface $jwt;
    private LoggerInterface $logger;
    
    public function __construct(HttpClientInterface $httpClient, RequestStack $requestStack, JWTTokenManagerInterface $jwt, LoggerInterface $logger)
    {
        $this->httpClient = $httpClient;
        $this->requestStack = $requestStack;
        $this->jwt = $jwt;
        $this->logger = $logger;
    }

    public function onAuthenticationSuccess(InteractiveLoginEvent $event)
    {
        $this->logger->info('EVENT FIRED');

        $user = $event->getAuthenticationToken()->getUser();

        $token = $this->jwt->create($user);
        if($token){
            $this->logger->info('TOKEN GÉNÉRÉ');
        }

        // Création du cookie
        $cookie = Cookie::create('token')
            ->withValue($token)
            ->withExpires(time() + 3600) // Expire dans 1 heure
            ->withPath('/api/')
            ->withSecure(true) // Pour HTTPS seulement
            ->withHttpOnly(true);

            $this->logger->info('COOKIE AJOUTE');
        // Ajout du cookie à la réponse
        $this->requestStack->getCurrentRequest()->attributes->set('jwt_cookie', $cookie);
        $response = new Response();
        $response->headers->setCookie($cookie);
        $response->send();
    }
}