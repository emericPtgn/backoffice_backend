<?php

namespace App\Controller\API;
use App\Service\MarkerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class MarkerAPIController extends AbstractController {

    private MarkerService $markerService;
    private SerializerInterface $serializer;

    public function __construct(MarkerService $markerService, SerializerInterface $serializer){
        $this->markerService = $markerService;
        $this->serializer = $serializer;
    }

    #[Route('/api/marker', name: 'api_marker_addNew', methods: ['POST'])]
    public function addMarker(Request $request){
        $requestDatas = json_decode($request->getContent(), true);
        $newMarker = $this->markerService->addMarker($requestDatas);
        $serializedMarker = $this->serializer->serialize($newMarker, 'json', ['groups' => 'emplacement']);
        return new JsonResponse($serializedMarker, 200, [], true);
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
        $serializedMarker = $this->serializer->serialize($marker, 'json', ['groups' => 'emplacement']);
        return new JsonResponse($serializedMarker, 200, [], true);
    }
    #[Route('/api/marker', name: 'api_marker_getAllMarkers', methods: ['GET'])]
    public function getAllMarker(){
        $markers = $this->markerService->getAllMarker();
        $serializedMarker = $this->serializer->serialize($markers, 'json', ['groups' => 'emplacement']);
        return new JsonResponse($serializedMarker, 200, [], true);
    }
}