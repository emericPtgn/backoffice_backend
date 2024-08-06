<?php

namespace App\Controller\API;
use App\Service\TypeCommerceService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class TypeCommerceAPIController extends AbstractController{

    private TypeCommerceService $typeCommerceService;
    private SerializerInterface $serializer;

    public function __construct(TypeCommerceService $typeCommerceService, SerializerInterface $serializer){
        $this->typeCommerceService = $typeCommerceService;
        $this->serializer = $serializer;
    }

    #[Route('/api/type_commerce', name: 'api_typeCommerce_addNew', methods: ['POST'])]
    public function addNewtypeCommerce(Request $request) : JsonResponse {
        $requestDatas = json_decode($request->getContent(), true);
        $newtypeCommerce = $this->typeCommerceService->addNewType($requestDatas);
        $serializedTypeCommerce = $this->serializer->serialize($newtypeCommerce, 'json', ['groups' => 'commerce']);
        return new JsonResponse($serializedTypeCommerce, 200, [], true);
    }

    #[Route('/api/type_commerce/{id}', name: 'api_typeCommerce_update', methods: ['PUT'])]
    public function updateTypeCommerce(string $id, Request $request) : JsonResponse {
        $requestDatas = json_decode($request->getContent(), true);
        $newTypeCommerce = $this->typeCommerceService->updateTypeCommerce($id, $requestDatas);
        $serializedTypeCommerce = $this->serializer->serialize($newTypeCommerce, 'json', ['groups' => 'commerce']);
        return new JsonResponse($serializedTypeCommerce, 200, [], true);
    }

    #[Route('/api/type_commerce/{id}', name: 'api_typeCommerce_get', methods: ['GET'])]
    public function getTypeCommerce(string $id) : JsonResponse {
        $typeCommerce = $this->typeCommerceService->getTypeCommerce($id);
        $serializedTypeCommerce = $this->serializer->serialize($typeCommerce, 'json', ['groups' => 'commerce']);
        return new JsonResponse($serializedTypeCommerce, 200, [], true);
    }

    #[Route('/api/type_commerce', name: 'api_typeCommerce_getAll', methods: ['GET'])]
    public function getAllTypesCommerces() : JsonResponse {
        $allTypesCommerces = $this->typeCommerceService->getAllTypesCommerces();
        $serializedTypesCommerces = $this->serializer->serialize($allTypesCommerces, 'json', ['groups' => 'commerce']);
        return new JsonResponse($serializedTypesCommerces, 200, [], true);
    }

    #[Route('/api/type_commerce/{id}', name: 'api_typeCommerce_remove', methods: ['DELETE'])]
    public function removeTypeCommerce(string $id) : JsonResponse {
        $removedTypeCommerce = $this->typeCommerceService->removeTypeCommerce($id);
        return new JsonResponse($removedTypeCommerce, 200, [], false);
    }
}