<?php

namespace App\Controller\API;

use App\Service\CommerceService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class CommerceAPIController extends AbstractController {

    private CommerceService $commerceService;
    private SerializerInterface $serializer;

    public function __construct(CommerceService $commerceService, SerializerInterface $serializer){
        $this->commerceService = $commerceService;
        $this->serializer = $serializer;
    }

    #[Route('/api/commerce', name:'api_commerce_addCommerce', methods: ['POST'])]
    public function addCommerce(Request $request) : JsonResponse {
        $requestDatas = json_decode($request->getContent(), true);
        $newCommerce = $this->commerceService->addNewCommerce($requestDatas);
        $serializedNewCommerce = $this->serializer->serialize($newCommerce, 'json');
        return new JsonResponse($serializedNewCommerce, 200, [], true);
    }

    #[Route('/api/commerce/{id}', name:'api_commerce_updateCommerce', methods: ['PUT'])]
    public function updateCommerce($id, Request $request) : JsonResponse {
        $requestDatas = json_decode($request->getContent(), true);
        $updatedCommerce = $this->commerceService->updateCommerce($id, $requestDatas);
        return new JsonResponse($updatedCommerce, 200, [], false);
    }

    #[Route('/api/commerce/{id}', name:'api_commerce_getCommerce', methods: ['GET'])]
    public function getCommerce($id) : JsonResponse {
        $commerce = $this->commerceService->getCommerce($id);
        $serializedCommerce = $this->serializer->serialize($commerce, 'json', ['groups' => 'commerce']);
        return new JsonResponse($serializedCommerce, 200, [], true);
    }

    #[Route('/api/commerce', name:'api_commerce_getAllCommerces', methods: ['GET'])]
    public function getAllCommerces() : JsonResponse {
        $listCommerces = $this->commerceService->getAllCommerces();
        $serializedList = $this->serializer->serialize($listCommerces, 'json', ['groups' => 'commerce']);
        return new JsonResponse($serializedList, 200, [], true);
    }

}