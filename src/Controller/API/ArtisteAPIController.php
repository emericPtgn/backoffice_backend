<?php

namespace App\Controller\API;

use App\Service\ArtisteService;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\String\Slugger\SluggerInterface;


#[AsController]
class ArtisteAPIController extends AbstractController {

    private ArtisteService $artisteService;
    private SerializerInterface $serializer;

    public function __construct(ArtisteService $artisteService, SerializerInterface $serializer){
        $this->artisteService = $artisteService;
        $this->serializer = $serializer;
    }


    
    #[Route('/api/artiste', name: 'app_artiste_addnew', methods: ['POST'])]
    public function addArtiste(Request $request): JsonResponse
    {
        $requestDatas = $request->request->all();
        $imageFile = $request->files->get('photo'); 
        dump($imageFile);
        $artiste = $this->artisteService->addArtiste($requestDatas, $imageFile);
        $serializedArtiste = $this->serializer->serialize($artiste, 'json', ['groups' => 'artiste']);
    
        return new JsonResponse($serializedArtiste, 200, [], true);
    }
    

    #[Route('/api/artiste/{id}', name: 'app_artiste_remove', methods:['DELETE'])]
    public function removeArtiste(string $id) : JsonResponse {
        $response = $this->artisteService->removeArtiste($id);
        $serializedResponse = $this->serializer->serialize($response, 'json', ['groups' => 'artiste']);
        return new JsonResponse($serializedResponse, 200, [], true);
    }

    #[Route('/api/artiste/{id}', name: 'app_artiste_update', methods:['POST'])]
    // utilisation méthode POST, car la méthode PUT n'est pas construite pour traiter nativement les fichiers multipart-form-data
    public function updateArtiste(Request $request, string $id) : JsonResponse {
        $requestDatas = $request->request->all();
        $imageFile = $request->files->get('photo'); 
        dump($imageFile);
        $response = $this->artisteService->updateArtiste($id,$requestDatas, $imageFile);
        $serializedResponse = $this->serializer->serialize($response, 'json', ['groups' => 'artiste']);
        return new JsonResponse($serializedResponse, 200, [], true);
    }

    #[Route('/public/api/artiste/{id}', name: 'app_artiste_get', methods:['GET'])]
    public function getArtiste(string $id) : JsonResponse {
        $response = $this->artisteService->getArtiste($id);
        $serializedResponse = $this->serializer->serialize($response, 'json', ['groups' => 'artiste']);
        return new JsonResponse($serializedResponse, 200, [], true);
    }

    #[Route('/public/api/artiste', name: 'app_artiste_get_liste', methods:['GET'])]
    public function getListeArtistes() : JsonResponse {
        $response = $this->artisteService->getArtistes();
        $serializedResponse = $this->serializer->serialize($response, 'json', ['groups' => 'artiste']);
        return new JsonResponse($serializedResponse, 200, [], true);
    }


}