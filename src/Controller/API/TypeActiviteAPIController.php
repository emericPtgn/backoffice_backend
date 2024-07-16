<?php

namespace App\Controller\API;
use App\Service\TypeActiviteService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class TypeActiviteAPIController {

    private TypeActiviteService $typeActiviteService;

    public function __construct(TypeActiviteService $typeActiviteService){
        $this->typeActiviteService = $typeActiviteService;
    }

    #[Route('/api/type_activity', name: 'api_typeActivity_addNew', methods: ['POST'])]
    public function addNewTypeActivity(Request $request) : JsonResponse {
        $requestDatas = json_decode($request->getContent(), true);
        $newTypeActivity = $this->typeActiviteService->addNewType($requestDatas);
        return new JsonResponse($newTypeActivity, 200, [], false);
    }

    #[Route('/api/type_activity/{id}', name: 'api_typeActivity_update', methods: ['PUT'])]
    public function updateTypeActivity(string $id, Request $request) : JsonResponse {
        $requestDatas = json_decode($request->getContent(), true);
        $newTypeActivity = $this->typeActiviteService->updateTypeActivity($id, $requestDatas);
        return new JsonResponse($newTypeActivity, 200, [], false);
    }

    #[Route('/api/type_activity/{id}', name: 'api_typeActivity_get', methods: ['GET'])]
    public function getTypeActivity(string $id) : JsonResponse {
        $typeActivity = $this->typeActiviteService->getTypeActivity($id);
        return new JsonResponse($typeActivity, 200, [], false);
    }

    #[Route('/api/type_activity', name: 'api_typeActivity_getAll', methods: ['GET'])]
    public function getAllTypesActivities() : JsonResponse {
        $allTypesActivies = $this->typeActiviteService->getAllTypesActivities();
        return new JsonResponse($allTypesActivies, 200, [], false);
    }

    #[Route('/api/type_activity/{id}', name: 'api_typeActivity_remove', methods: ['DELETE'])]
    public function removeTypeActivity(string $id) : JsonResponse {
        $removedTypeActivity = $this->typeActiviteService->removeTypeActivity($id);
        return new JsonResponse($removedTypeActivity, 200, [], false);
    }
}