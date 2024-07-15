<?php

namespace App\Controller\API;

use App\Service\SceneService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class SceneAPIController {

    private SceneService $sceneService;

    public function __construct(SceneService $sceneService){
        $this->sceneService = $sceneService;
    }

    #[Route('/api/scene', name: 'api_scene_new', methods: ['POST'])]
    public function addScene(Request $request){
        $requestDatas = json_decode($request->getContent(), true);
        $newScene = $this->sceneService->addScene($requestDatas);
        return new JsonResponse($newScene, 200, [], false);
    }
    #[Route('/api/scene/{id}', name: 'api_scene_update', methods: ['PUT'])]
    public function updateScene(string $id, Request $request){
        $requestDatas = json_decode($request->getContent(), true);
        $updatedScene = $this->sceneService->updateScene($id, $requestDatas);
        return new JsonResponse($updatedScene, 200, [], false);
    }
    #[Route('/api/scene/{id}', name: 'api_scene_get', methods: ['GET'])]
    public function getScene(string $id){
        $scene = $this->sceneService->getScene($id);
        return new JsonResponse($scene, 200, [], false);
    }
    #[Route('/api/scene', name: 'api_scene_getAll', methods: ['GET'])]
    public function getAllScenes(string $id){
        $allScenes = $this->sceneService->getAllScenes();
        return new JsonResponse($allScenes, 200, [], false);
    }

}