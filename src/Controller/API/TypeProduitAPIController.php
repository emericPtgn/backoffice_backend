<?php

namespace App\Controller\API;
use App\Service\TypeProduitService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class TypeProduitAPIController extends AbstractController {

    private TypeProduitService $typeProduitService;
    private SerializerInterface $serializer;

    public function __construct(TypeProduitService $typeProduitService, SerializerInterface $serializer){
        $this->typeProduitService = $typeProduitService;
        $this->serializer = $serializer;
    }

    #[Route('/api/type_produit', name: 'api_typeProduit_addNew', methods: ['POST'])]
    public function addNewtypeProduct(Request $request) : JsonResponse {
        $requestDatas = json_decode($request->getContent(), true);
        $newTypeProduit = $this->typeProduitService->addNewType($requestDatas);
        $serializedTypeProduit = $this->serializer->serialize($newTypeProduit, 'json');
        return new JsonResponse($serializedTypeProduit, 200, [], true);
    }

    #[Route('/api/type_produit/{id}', name: 'api_typeProduit_update', methods: ['PUT'])]
    public function updateTypeProduct(string $id, Request $request) : JsonResponse {
        $requestDatas = json_decode($request->getContent(), true);
        $newTypeProduit = $this->typeProduitService->updateTypeProduct($id, $requestDatas);
        return new JsonResponse($newTypeProduit, 200, [], false);
    }

    #[Route('/api/type_produit/{id}', name: 'api_typeProduit_get', methods: ['GET'])]
    public function getTypeProduct(string $id) : JsonResponse {
        $typeProduit = $this->typeProduitService->getTypeProduct($id);
        $serializedTypeProduit = $this->serializer->serialize($typeProduit, 'json');
        return new JsonResponse($serializedTypeProduit, 200, [], true);
    }

    #[Route('/api/type_produit', name: 'api_typeProduit_getAll', methods: ['GET'])]
    public function getAllTypesProducts() : JsonResponse {
        $allTypesProducts = $this->typeProduitService->getAllTypesProducts();
        $serializedTypeProduit = $this->serializer->serialize($allTypesProducts, 'json');
        return new JsonResponse($serializedTypeProduit, 200, [], true);
    }

    #[Route('/api/type_produit/{id}', name: 'api_typeProduit_remove', methods: ['DELETE'])]
    public function removeTypeProduct(string $id) : JsonResponse {
        $removedTypeProduit = $this->typeProduitService->removeTypeProduct($id);
        return new JsonResponse($removedTypeProduit, 200, [], false);
    }
}