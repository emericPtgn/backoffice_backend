<?php

namespace App\Controller\API;
use App\Service\ConcertService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class ConcertAPIController {

    private ConcertService $concertService;

    public function __construct(ConcertService $concertService)
    {
        $this->concertService = $concertService;
    }

    #[Route('/api/concert', name:'api_add_concert', methods: ['POST'])]
    public function addConcert(Request $request) : JsonResponse{
        $requestDatas = json_decode($request->getContent(), true);
        $newConcert = $this->concertService->addNewConcert($requestDatas);
        return new JsonResponse($newConcert, 200, [], false);
    }

    #[Route('/api/concert/{id}', name:'api_update_concert', methods: ['PUT'])]
    public function updateConcert(string $id, Request $request) : JsonResponse{
        $requestDatas = json_decode($request->getContent(), true);
        $updatedConcert = $this->concertService->updateConcert($id, $requestDatas);
        return new JsonResponse($updatedConcert, 200, [], false);
    }
    #[Route('/api/concert/{id}', name:'api_get_concert', methods: ['GET'])]
    public function getConcert(string $id) : JsonResponse{
        $concert = $this->concertService->getConcert($id);
        return new JsonResponse($concert, 200, [], false);
    }
    #[Route('/api/concert', name:'api_get_concerts_liste', methods: ['GET'])]
    public function getAllConcert() : JsonResponse{
        $allConcerts = $this->concertService->getAllConcerts();
        return new JsonResponse($allConcerts, 200, [], false);
    }

    #[Route('/api/concert/{id}', name:'api_delete_concert', methods: ['DELETE'])]
    public function removeConcert(string $id) : JsonResponse{
        $response = $this->concertService->removeConcert($id);
        return new JsonResponse($response, 200, [], false);
    }


}