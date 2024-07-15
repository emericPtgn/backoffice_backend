<?php

namespace App\Controller\API;

use App\Service\CommerceService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class CommerceAPIController {

    private CommerceService $commerceService;
    public function __construct(CommerceService $commerceService){
        $this->commerceService = $commerceService;
    }

    #[Route('/api/commerce', name:'api_commerce_addCommerce', methods: ['POST'])]
    public function addCommerce(Request $request) : JsonResponse {
        $requestDatas = json_decode($request->getContent(), true);
        $newCommerce = $this->commerceService->addNewCommerce($requestDatas);
        return new JsonResponse($newCommerce, 200, [], false);
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
        return new JsonResponse($commerce, 200, [], false);
    }

    #[Route('/api/commerce', name:'api_commerce_getAllConcerts', methods: ['GET'])]
    public function getAllCommerces() : JsonResponse {
        $listCommerces = $this->commerceService->getAllCommerces();
        return new JsonResponse($listCommerces, 200, [], false);
    }

}