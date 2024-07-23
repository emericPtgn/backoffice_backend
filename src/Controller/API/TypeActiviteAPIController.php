<?php

namespace App\Controller\API;
use App\Service\TypeActiviteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class TypeActiviteAPIController extends AbstractController {

    private TypeActiviteService $typeActiviteService;
    private SerializerInterface $serializer;

    public function __construct(TypeActiviteService $typeActiviteService, SerializerInterface $serializer){
        $this->typeActiviteService = $typeActiviteService;
        $this->serializer = $serializer;
    }

    #[Route('/api/type_activity', name: 'api_typeActivity_addNew', methods: ['POST'])]
    public function addNewTypeActivity(Request $request) : JsonResponse {
        $requestDatas = json_decode($request->getContent(), true);
        $newTypeActivity = $this->typeActiviteService->addNewType($requestDatas);
        $serializedTypeActivity = $this->serializer->serialize($newTypeActivity, 'json', ['groups' => 'activite']);
        return new JsonResponse($serializedTypeActivity, 200, [], true);
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
        $serializedTypeActivity = $this->serializer->serialize($typeActivity, 'json', ['groups' => 'activite']);
        return new JsonResponse($serializedTypeActivity, 200, [], true);
    }

    #[Route('/api/type_activity', name: 'api_typeActivity_getAll', methods: ['GET'])]
    public function getAllTypesActivities() : JsonResponse {
        $allTypesActivies = $this->typeActiviteService->getAllTypesActivities();
        $serializedTypeActivities = $this->serializer->serialize($allTypesActivies, 'json', ['groups' => 'activite']);
        return new JsonResponse($serializedTypeActivities, 200, [], true);
    }

    #[Route('/api/type_activity/{id}', name: 'api_typeActivity_remove', methods: ['DELETE'])]
    public function removeTypeActivity(string $id) : JsonResponse {
        $removedTypeActivity = $this->typeActiviteService->removeTypeActivity($id);
        return new JsonResponse($removedTypeActivity, 200, [], false);
    }
}