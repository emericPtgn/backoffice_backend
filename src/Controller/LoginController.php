<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\JsonResponse;

class LoginController extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route(path: '/login', name: 'app_login', methods: ['POST'])]
    public function login(Request $request, AuthenticationUtils $authenticationUtils): JsonResponse
    {

        try {
            $data = json_decode($request->getContent(), true);
            $username = $data['_username'] ?? '';
            $password = $data['_password'] ?? '';
            $this->logger->info($username);
            $this->logger->info($password);

            // Logique d'authentification ici
            $error = $authenticationUtils->getLastAuthenticationError();
            $lastUsername = $authenticationUtils->getLastUsername();

            if ($error) {
                return new JsonResponse(['success' => false, 'message' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
            }

            return new JsonResponse(['success' => true]);
        } catch (\Exception $e) {
            $this->logger->error('An error occurred in login controller: ' . $e->getMessage());
            return new JsonResponse(['success' => false, 'message' => 'An error occurred during login'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
