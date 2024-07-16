<?php

namespace App\Controller\API;
use App\Service\TypeProduitService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class TypeProduitAPIController {

    private TypeProduitService $typeProduitService;

    public function __construct(TypeProduitService $typeProduitService){
        $this->typeProduitService = $typeProduitService;
    }

    #[Route('/api/type_produit', name: 'api_typeProduit_addNew', methods: ['POST'])]
    public function addNewtypeProduct(Request $request) : JsonResponse {
        $requestDatas = json_decode($request->getContent(), true);
        $newTypeProduit = $this->typeProduitService->addNewType($requestDatas);
        return new JsonResponse($newTypeProduit, 200, [], false);
    }

    #[Route('/api/type_produit/{id}', name: 'api_typeProduit_update', methods: ['PUT'])]
    public function updateTypeProduct(string $id, Request $request) : JsonResponse {
        $requestDatas = json_decode($request->getContent(), true);
        $newTypeProduit = $this->typeProduitService->updateTypeProduct($id, $requestDatas);
        return new JsonResponse($newTypeProduit, 200, [], false);
    }

    #[Route('/api/type_produit/{id}', name: 'api_typeProduit_get', methods: ['GET'])]
    public function getTypeProduct(string $id) : JsonResponse {
        $typeProduct = $this->typeProduitService->getTypeProduct($id);
        return new JsonResponse($typeProduct, 200, [], false);
    }

    #[Route('/api/type_produit', name: 'api_typeProduit_getAll', methods: ['GET'])]
    public function getAllTypesProducts() : JsonResponse {
        $allTypesProducts = $this->typeProduitService->getAllTypesProducts();
        return new JsonResponse($allTypesProducts, 200, [], false);
    }

    #[Route('/api/type_produit/{id}', name: 'api_typeProduit_remove', methods: ['DELETE'])]
    public function removeTypeProduct(string $id) : JsonResponse {
        $removedTypeProduit = $this->typeProduitService->removeTypeProduct($id);
        return new JsonResponse($removedTypeProduit, 200, [], false);
    }
}