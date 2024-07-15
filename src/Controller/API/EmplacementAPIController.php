<?php

namespace App\Controller\API;
use App\Service\EmplacementService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class EmplacementAPIController {

    private EmplacementService $emplacementService;

    public function __construct(EmplacementService $emplacementService){
        $this->emplacementService = $emplacementService;
    }

    #[Route('/api/emplacement', name: 'api_emplacement_addNew', methods: ['POST'])]
    public function addEmplacement(Request $request){
        $requestDatas = json_decode($request->getContent(), true);
        $newEmplacement = $this->emplacementService->addNewEmplacement($requestDatas);
        return new JsonResponse($newEmplacement, 200, [], false);
    }

    #[Route('/api/emplacement/{id}', name: 'api_emplacement_update', methods: ['PUT'])]
    public function updateEmplacement(string $id, Request $request){
        $requestDatas = json_decode($request->getContent(), true);
        $updatedEmplacement = $this->emplacementService->updateEmplacement($id, $requestDatas);
        return new JsonResponse($updatedEmplacement, 200, [], false);
    }

    #[Route('/api/emplacement/{id}', name: 'api_emplacement_get', methods: ['GET'])]
    public function getEmplacement(string $id){
        $emplacement = $this->emplacementService->getEmplacement($id);
        return new JsonResponse($emplacement, 200, [], false);
    }

    #[Route('/api/emplacement', name: 'api_emplacement_getAll', methods: ['GET'])]
    public function getAllEmplacements(string $id){
        $allEmplacements = $this->emplacementService->getAllEmplacements();
        return new JsonResponse($allEmplacements, 200, [], false);
    }

}