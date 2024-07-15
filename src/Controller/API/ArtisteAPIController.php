<?php

namespace App\Controller\API;

use App\Service\ArtisteService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


class ArtisteAPIController {

    private ArtisteService $artisteService;

    public function __construct(ArtisteService $artisteService){
        $this->artisteService = $artisteService;
    }

    #[Route('/api/artiste', name: 'app_artiste_addnew', methods:['POST'])]
    public function addArtiste(Request $request) : JsonResponse {
        $requestDatas = json_decode($request->getContent(), true);
        $artiste = $this->artisteService->addArtiste($requestDatas);
        return new JsonResponse($artiste, 200, [], false);
    }

    #[Route('/api/artiste/{id}', name: 'app_artiste_remove', methods:['DELETE'])]
    public function removeArtiste(string $id) : JsonResponse {
        $response = $this->artisteService->removeArtiste($id);
        return new JsonResponse($response, 200, [], false);
    }

    #[Route('/api/artiste/{id}', name: 'app_artiste_update', methods:['PUT'])]
    public function updateArtiste(Request $request, string $id) : JsonResponse {
        $requestDatas = json_decode($request->getContent(), false);
        $response = $this->artisteService->updateArtiste($id,$requestDatas);
        return new JsonResponse($response, 200, [], false);
    }

    #[Route('/api/artiste/{id}', name: 'app_artiste_update', methods:['GET'])]
    public function getArtiste(string $id) : JsonResponse {
        $response = $this->artisteService->getArtiste($id);
        return new JsonResponse($response, 200, [], false);
    }

    #[Route('/api/artiste', name: 'app_artiste_get_liste', methods:['GET'])]
    public function getListeArtistes(string $id) : JsonResponse {
        $response = $this->artisteService->getArtistes();
        return new JsonResponse($response, 200, [], false);
    }


}