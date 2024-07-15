<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Controller\UserAPIController;

class UserController extends AbstractController 
{
    private UserService $userService;
    private HttpClientInterface $httpClient;

    public function __construct(UserService $userService, HttpClientInterface $httpClient){
        $this->userService = $userService;
        $this->httpClient = $httpClient;
    }

    #[Route('/user', name: 'app_user')]
    public function index(Request $request): Response
    {
        $username = $request->query->get('username');
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

}
