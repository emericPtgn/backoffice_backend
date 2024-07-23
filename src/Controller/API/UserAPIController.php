<?php

namespace App\Controller\API;

use App\Service\UserService;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserAPIController extends AbstractController
{
    private UserService $userService;
    private SerializerInterface $serializer;

    public function __construct(UserService $userService, SerializerInterface $serializer){
        $this->userService = $userService;
        $this->serializer = $serializer;
    }


    #[Route('/api/user', name: 'api_user_new_user', methods: ['POST'])]
    public function addUser(Request $request) : JsonResponse
    {
        $requestDatas = json_decode($request->getContent(), true);
        if(json_last_error() !== JSON_ERROR_NONE){
            return new JsonResponse(['erreor' => 'invalid Json'], Response::HTTP_BAD_REQUEST);
        }
        $response = $this->userService->addUser($requestDatas);
        return new JsonResponse($response, 200, [], false);
    }

    #[Route('/api/user', name: 'api_user_remove_user', methods: ['DELETE'])]
    public function removeUser(Request $request)
    {
        $username = $request->query->get('username');
        $response = $this->userService->removeUser($username);
        return new JsonResponse($response, 200, [], false);
    }

    #[Route('/api/user', name: 'api_user_update_user', methods: ['PUT'])]
    public function updateUser(Request $request)
    {
        $requestDatas = json_decode($request->getContent(), true);
        $response = $this->userService->updateUser($requestDatas);
        return new JsonResponse($response, 200, [], false);
    }

    #[Route('/api/user', name: 'api_user_get_user', methods: ['GET'])]
    // declaration getUserDatas because of getUser from abstractController 
    public function getUsers()
    {
        $response = $this->userService->getUsers();
        $serializedResponse = $this->serializer->serialize($response, 'json');
        return new JsonResponse($serializedResponse, 200, [], true);
    }


}
