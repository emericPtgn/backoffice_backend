<?php

namespace App\Controller\API;
use App\Service\MarkerService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class MarkerAPIEmplacement {

    private MarkerService $markerService;

    public function __construct(MarkerService $markerService){
        $this->markerService = $markerService;
    }

    #[Route('/api/marker', name: 'api_marker_addNew', methods: ['POST'])]
    public function addMarker(Request $request){
        $requestDatas = json_decode($request->getContent(), true);
        $newMarker = $this->markerService->addMarker($requestDatas);
        return new JsonResponse($newMarker, 200, [], false);
    }
    #[Route('/api/marker/{id}', name: 'api_marker_update', methods: ['PUT'])]
    public function updateMarker(string $id, Request $request){
        $requestDatas = json_decode($request->getContent(), true);
        $newMarker = $this->markerService->addMarker($requestDatas);
        return new JsonResponse($newMarker, 200, [], false);
    }
    #[Route('/api/marker/{id}', name: 'api_marker_get', methods: ['GET'])]
    public function getMarker(string $id){
        $marker = $this->markerService->getMarker($id);
        return new JsonResponse($marker, 200, [], false);
    }
    #[Route('/api/marker', name: 'api_marker_getAllMarkers', methods: ['GET'])]
    public function getAllMarker(){
        $marker = $this->markerService->getAllMarker();
        return new JsonResponse($marker, 200, [], false);
    }
}