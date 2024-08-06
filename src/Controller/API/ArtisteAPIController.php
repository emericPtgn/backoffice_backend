<?php

namespace App\Controller\API;

use App\Service\ArtisteService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[AsController]
class ArtisteAPIController extends AbstractController {

    private ArtisteService $artisteService;
    private SerializerInterface $serializer;

    public function __construct(ArtisteService $artisteService, SerializerInterface $serializer){
        $this->artisteService = $artisteService;
        $this->serializer = $serializer;
    }

    #[Route('/api/artiste', name: 'app_artiste_addnew', methods:['POST'])]
    public function addArtiste(Request $request) : JsonResponse {
        $requestDatas = json_decode($request->getContent(), true);
        $artiste = $this->artisteService->addArtiste($requestDatas);
        $serializedArtiste = $this->serializer->serialize($artiste, 'json', ['groups' => 'artiste', 'social']);
        return new JsonResponse($serializedArtiste, 200, [], true);
    }

    #[Route('/api/artiste/{id}', name: 'app_artiste_remove', methods:['DELETE'])]
    public function removeArtiste(string $id) : JsonResponse {
        $response = $this->artisteService->removeArtiste($id);
        return new JsonResponse($response, 200, [], false);
    }

    #[Route('/api/artiste/{id}', name: 'app_artiste_update', methods:['PUT'])]
    public function updateArtiste(Request $request, string $id) : JsonResponse {
        $requestDatas = json_decode($request->getContent(), true);
        $response = $this->artisteService->updateArtiste($id,$requestDatas);
        $serializedResponse = $this->serializer->serialize($response, 'json', ['groups' => 'artiste', 'activite', "social"]);
        return new JsonResponse($serializedResponse, 200, [], true);
    }

    #[Route('/api/artiste/{id}', name: 'app_artiste_get', methods:['GET'])]
    public function getArtiste(string $id) : JsonResponse {
        $response = $this->artisteService->getArtiste($id);
        $serializedResponse = $this->serializer->serialize($response, 'json', ['groups' => 'artiste', 'activite', 'social']);
        return new JsonResponse($serializedResponse, 200, [], true);
    }

    #[Route('/api/artiste', name: 'app_artiste_get_liste', methods:['GET'])]
    public function getListeArtistes() : JsonResponse {
        $response = $this->artisteService->getArtistes();
        $serializedResponse = $this->serializer->serialize($response, 'json', ['groups' => 'artiste', 'activite', 'social']);
        return new JsonResponse($serializedResponse, 200, [], true);
    }


}