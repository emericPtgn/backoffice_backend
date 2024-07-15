<?php

namespace App\Controller\API;

use App\Service\ActiviteService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


class ActiviteAPIController {

    private ActiviteService $activiteService;

    public function __construct(ActiviteService $activiteService){
        $this->activiteService = $activiteService;
    }

    #[Route('/api/activity', name: 'api_activity_new_activity', methods: ['POST'])]
    public function addActivity(Request $request) : JsonResponse
    {
        $requestDatas = json_decode($request->getContent(), true);
        if(json_last_error() !== JSON_ERROR_NONE){
            return new JsonResponse(['erreor' => 'invalid Json'], Response::HTTP_BAD_REQUEST);
        }
        $activity = $this->activiteService->addActivity($requestDatas);
        return new JsonResponse($activity, 200, [], false);
    }

    #[Route('/api/activity/{id}', name: 'api_activity_delete_activity', methods: ['DELETE'])]
    public function deleteActivity(string $id) : JsonResponse
    {
        $response = $this->activiteService->removeActivity($id);
        return new JsonResponse($response, 200, [], false);
    }

    // gère la mise à jour d'une activity
    // prend un string en argument et retourne du JSON
    #[Route('/api/activity/{id}', name: 'api_activity_update_activity', methods: ['PUT'])]
    public function updateActivity(Request $request, string $id) : JsonResponse
    {
        $requestDatas = json_decode($request->getContent(), true);
        $activity = $this->activiteService->updateActivity($id, $requestDatas);
        return new JsonResponse($activity, 200, [], false);
    }

    #[Route('/api/activity', name: 'api_activity_get_activities', methods: ['GET'])]
    public function getActivities() : JsonResponse
    {
        $activities = $this->activiteService->getActivities();
        return new JsonResponse($activities, 200, [], false);
    }

    #[Route('/api/activity/{id}', name: 'app_activity_get_activity', methods:['GET'])]
    public function getActivity(string $id): JsonResponse
    {
        $activity = $this->activiteService->getActivity($id);
        return new JsonResponse($activity, 200, [], false);
    }

}