<?php

namespace App\Controller\API;
use App\Service\TypeCommerceService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class TypeCommerceAPIController {

    private TypeCommerceService $typeCommerceService;

    public function __construct(TypeCommerceService $typeCommerceService){
        $this->typeCommerceService = $typeCommerceService;
    }

    #[Route('/api/type_commerce', name: 'api_typeCommerce_addNew', methods: ['POST'])]
    public function addNewtypeCommerce(Request $request) : JsonResponse {
        $requestDatas = json_decode($request->getContent(), true);
        $newtypeCommerce = $this->typeCommerceService->addNewType($requestDatas);
        return new JsonResponse($newtypeCommerce, 200, [], false);
    }

    #[Route('/api/type_commerce/{id}', name: 'api_typeCommerce_update', methods: ['PUT'])]
    public function updateTypeCommerce(string $id, Request $request) : JsonResponse {
        $requestDatas = json_decode($request->getContent(), true);
        $newTypeCommerce = $this->typeCommerceService->updateTypeCommerce($id, $requestDatas);
        return new JsonResponse($newTypeCommerce, 200, [], false);
    }

    #[Route('/api/type_commerce/{id}', name: 'api_typeCommerce_get', methods: ['GET'])]
    public function getTypeCommerce(string $id) : JsonResponse {
        $typeCommerce = $this->typeCommerceService->getTypeCommerce($id);
        return new JsonResponse($typeCommerce, 200, [], false);
    }

    #[Route('/api/type_commerce', name: 'api_typeCommerce_getAll', methods: ['GET'])]
    public function getAllTypesCommerces() : JsonResponse {
        $allTypesCommerces = $this->typeCommerceService->getAllTypesCommerces();
        return new JsonResponse($allTypesCommerces, 200, [], false);
    }

    #[Route('/api/type_commerce/{id}', name: 'api_typeCommerce_remove', methods: ['DELETE'])]
    public function removeTypeCommerce(string $id) : JsonResponse {
        $removedTypeCommerce = $this->typeCommerceService->removeTypeCommerce($id);
        return new JsonResponse($removedTypeCommerce, 200, [], false);
    }
}