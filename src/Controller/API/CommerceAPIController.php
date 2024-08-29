<?php

namespace App\Controller\API;

use Psr\Log\LoggerInterface;
use App\Service\CommerceService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CommerceAPIController extends AbstractController {

    private CommerceService $commerceService;
    private SerializerInterface $serializer;
    private LoggerInterface $logger;

    public function __construct(CommerceService $commerceService, SerializerInterface $serializer, LoggerInterface $logger){
        $this->commerceService = $commerceService;
        $this->serializer = $serializer;
        $this->logger = $logger;
    }

    #[Route('/api/commerce', name:'api_commerce_addCommerce', methods: ['POST'])]
    public function addCommerce(Request $request): JsonResponse {
        $requestDatas = json_decode($request->getContent(), true);
        $newCommerce = $this->commerceService->addNewCommerce($requestDatas);
        $serializedNewCommerce = $this->serializer->serialize($newCommerce, 'json', ['groups' => 'commerce']);
        return new JsonResponse($serializedNewCommerce, 200, [], true);
    }
    
    #[Route('/api/commerce/{id}', name:'api_commerce_updateCommerce', methods: ['PUT'])]
    public function updateCommerce(string $id, Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        $updatedCommerce = $this->commerceService->updateCommerce($id, $requestData);
        $serializedNewCommerce = $this->serializer->serialize($updatedCommerce, 'json', ['groups' => 'commerce']);
        return new JsonResponse($serializedNewCommerce, 200, [], true);
    }

    #[Route('/public/api/commerce/{id}', name:'api_commerce_getCommerce', methods: ['GET'])]
    public function getCommerce($id) : JsonResponse {
        $commerce = $this->commerceService->getCommerce($id);
        $serializedCommerce = $this->serializer->serialize($commerce, 'json', ['groups' => 'commerce']);
        return new JsonResponse($serializedCommerce, 200, [], true);
    }

    #[Route('/public/api/commerce', name:'api_commerce_getAllCommerces', methods: ['GET'])]
    public function getAllCommerces() : JsonResponse {
        $listCommerces = $this->commerceService->getAllCommerces();
        $serializedList = $this->serializer->serialize($listCommerces, 'json', ['groups' => 'commerce']);
        return new JsonResponse($serializedList, 200, [], true);
    }

    #[Route('/api/commerce/{id}', name:'api_commerce_deleteCommerce', methods: ['DELETE'])]
    public function deleteCommerce(string $id) : JsonResponse {
        $response = $this->commerceService->deleteCommerce($id);
        $serializedResponse = $this->serializer->serialize($response, 'json', ['groups' => 'commerce']);
        return new JsonResponse($serializedResponse, 200, [], true);
    }

}