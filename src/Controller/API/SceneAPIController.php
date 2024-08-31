<?php

namespace App\Controller\API;

use App\Service\SceneService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class SceneAPIController extends AbstractController {

    private SceneService $sceneService;
    private SerializerInterface $serializer;

    public function __construct(SceneService $sceneService, SerializerInterface $serializer){
        $this->sceneService = $sceneService;
        $this->serializer = $serializer;
    }

    #[Route('/api/scene', name: 'api_scene_new', methods: ['POST'])]
    public function addScene(Request $request){
        $requestDatas = json_decode($request->getContent(), true);
        $newScene = $this->sceneService->addScene($requestDatas);
        $serializedScene = $this->serializer->serialize($newScene, 'json');
        return new JsonResponse($serializedScene, 200, [], true);
    }

    
    #[Route('/api/scene/{id}', name: 'api_scene_update', methods: ['PUT'])]
    public function updateScene(string $id, Request $request){
        $requestDatas = json_decode($request->getContent(), true);
        $updatedScene = $this->sceneService->updateScene($id, $requestDatas);
        $serializedScene = $this->serializer->serialize($updatedScene, 'json');
        return new JsonResponse($serializedScene, 200, [], true);
    }
    #[Route('/public/api/scene/{id}', name: 'api_scene_get', methods: ['GET'])]
    public function getScene(string $id){
        $scene = $this->sceneService->getScene($id);
        $serializedScene = $this->serializer->serialize($scene, 'json');
        return new JsonResponse($serializedScene, 200, [], true);
    }
    #[Route('/public/api/scene', name: 'api_scene_getAll', methods: ['GET'])]
    public function getAllScenes(){
        $allScenes = $this->sceneService->getAllScenes();
        $serializedScenes = $this->serializer->serialize($allScenes, 'json');
        return new JsonResponse($serializedScenes, 200, [], true);
    }

}