<?php

namespace App\Controller\API;

use App\Document\User;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserAPIController extends AbstractController
{
    private UserService $userService;
    private SerializerInterface $serializer;

    private MailerInterface $mailer;

    public function __construct(UserService $userService, SerializerInterface $serializer, MailerInterface $mailer){
        $this->userService = $userService;
        $this->serializer = $serializer;
        $this->mailer = $mailer;
    }

    #[IsGranted('ROLE_ADMIN', '', 'you do not have access to users informations')]
    #[Route('/api/user', name: 'api_user_new_user', methods: ['POST'])]
    public function addUser(Request $request) : JsonResponse
    {
        $requestDatas = json_decode($request->getContent(), true);
        if(json_last_error() !== JSON_ERROR_NONE){
            return new JsonResponse(['erreor' => 'invalid Json'], Response::HTTP_BAD_REQUEST);
        }
        $response = $this->userService->addUser($requestDatas);
        $serializedResponse = $this->serializer->serialize($response, 'json', ['groups' => 'user']);
        return new JsonResponse($serializedResponse, 200, [], true);
    }

    #[IsGranted('ROLE_ADMIN', '', 'you do not have access to users informations')]
    #[Route('/api/user/{id}', name: 'api_user_remove_user', methods: ['DELETE'])]
    public function deleteUser(string $id)
    {
        $response = $this->userService->deleteUser($id);
        $serializedResponse = $this->serializer->serialize($response, 'json', ['groups' => 'user']);
        return new JsonResponse($serializedResponse, 200, [], true);
    }

    #[Route('/api/user/{id}', name: 'api_user_update_user', methods: ['PUT'])]
    public function updateUser(Request $request, string $id)
    {
        $requestDatas = json_decode($request->getContent(), true);
        $response = $this->userService->updateUser($requestDatas, $id);
        $serializedResponse = $this->serializer->serialize($response, 'json', ['groups' => 'user']);
        return new JsonResponse($serializedResponse, 200, [], true);
    }

    // #[IsGranted('ROLE_ADMIN', null, 'you do not have access to users informations')]
    #[Route('/api/user', name: 'api_user_get_users', methods: ['GET'])]
    // declaration getUserDatas because of getUser from abstractController 
    public function getUsers()
    {
        $response = $this->userService->getUsers();
        $serializedResponse = $this->serializer->serialize($response, 'json');
        return new JsonResponse($serializedResponse, 200, [], true);
    }


    #[Route('/api/user/activ-user', name: 'api_user_get_activ_user', methods: ['GET'])]
    public function getActivUser() : JsonResponse {
        $user = $this->getUser();
        $serializedUser = $this->serializer->serialize($user, 'json', ['groups' => 'user']);
        return new JsonResponse($serializedUser, 200, [], true); 
    }

    #[Route('/api/user/{id}', name: 'api_user_get_user', methods: ['GET'])]
    public function getThisUser(string $id){
        $user = $this->userService->getThisUser($id);
        $serializedUser = $this->serializer->serialize($user, 'json', ['groups' => 'user']);
        return new JsonResponse($serializedUser, 200, [], true);
    }
    #[Route('/api/user/askNewPassword/{id}', name: 'api_user_askNewPassowrd', methods: ['GET'])]
    public function askNewPassword(string $id, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $user = $this->userService->getThisUser($id);
        $response = $this->userService->sendResetPasswordEmail($user);
    
        return new JsonResponse($response, 200);
    }
    
    
    #[Route('/api/user/{token}', name: 'api_user_resetPassword', methods: ['POST'])]
    public function updatePassword(Request $request, string $token){
        $requestDatas = json_decode($request->getContent(), true);
        $response = $this->userService->resetPassword($requestDatas, $token);
        $serializedResponse = $this->serializer->serialize($response, 'json');
        return new JsonResponse($serializedResponse, 200, [], true);
    }


}
