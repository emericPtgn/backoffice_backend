<?php

namespace App\Controller\API;
use App\Service\MarkerService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class MarkerAPIController extends AbstractController {

    private MarkerService $markerService;
    private SerializerInterface $serializer;

    public function __construct(MarkerService $markerService, SerializerInterface $serializer){
        $this->markerService = $markerService;
        $this->serializer = $serializer;
    }

    #[Route('/api/marker', name: 'api_marker_addNew', methods: ['POST'])]
    public function addMarker(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $file = $request->files->get('icone');
    
        if ($file) {
            $uploadDirectory = $this->getParameter('kernel.project_dir') . '/public/uploads';
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII', $originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
    
            try {
                $file->move($uploadDirectory, $newFilename);
                $data['icone'] = "/uploads/$newFilename";
            } catch (FileException $e) {
                return new JsonResponse(['error' => 'Failed to upload file'], 500);
            }
        }
    
        $newMarker = $this->markerService->addMarker($data);
        $serializedMarker = $this->serializer->serialize($newMarker, 'json', ['groups' => 'marker']);
        return new JsonResponse($serializedMarker, 200, [], true);
    }
    
    #[Route('/api/marker/{id}', name: 'api_marker_update', methods: ['PUT'])]
    public function updateMarker(string $id, Request $request){
        $requestDatas = json_decode($request->getContent(), true);
        $newMarker = $this->markerService->updateMarker($id, $requestDatas);
        $serializedMarker = $this->serializer->serialize($newMarker, 'json', ['groups' => 'marker']);
        return new JsonResponse($serializedMarker, 200, [], true);
    }
    #[Route('/api/marker/{id}', name: 'api_marker_get', methods: ['GET'])]
    public function getMarker(string $id){
        $marker = $this->markerService->getMarker($id);
        $serializedMarker = $this->serializer->serialize($marker, 'json', ['groups' => 'marker']);
        return new JsonResponse($serializedMarker, 200, [], true);
    }
    #[Route('/api/marker', name: 'api_marker_getAllMarkers', methods: ['GET'])]
    public function getAllMarker(){
        $markers = $this->markerService->getAllMarker();
        $serializedMarker = $this->serializer->serialize($markers, 'json', ['groups' => 'marker']);
        return new JsonResponse($serializedMarker, 200, [], true);
    }

    #[Route('/api/marker/{id}', name: 'api_marker_deleteMarker', methods: ['DELETE'])]
    public function deleteMarker(string $id) : JsonResponse {
        $response = $this->markerService->deleteMarker($id);
        $serializedResponse = $this->serializer->serialize($response, 'json', ['groups' => 'marker']);
        return new JsonResponse($serializedResponse, 200, [], true);
    }
}