<?php

namespace App\Controller\API;

use App\Service\ActiviteService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;


class ActiviteAPIController extends AbstractController {

    private ActiviteService $activiteService;
    private SerializerInterface $serializer;
    private LoggerInterface $logger;

    public function __construct(ActiviteService $activiteService, SerializerInterface $serializer, LoggerInterface $logger){
        $this->activiteService = $activiteService;
        $this->serializer = $serializer;
        $this->logger = $logger;
    }

    #[Route('/api/activity', name: 'api_activity_new_activity', methods: ['POST'])]
    public function addActivity(Request $request) : JsonResponse
    {
        $requestDatas = json_decode($request->getContent(), true);
        if(json_last_error() !== JSON_ERROR_NONE){
            return new JsonResponse(['erreor' => 'invalid Json'], Response::HTTP_BAD_REQUEST);
        }
        $activity = $this->activiteService->addActivity($requestDatas);
        $serializedActivity = $this->serializer->serialize($activity, 'json', ['groups' => 'activite']);
        return new JsonResponse($serializedActivity, 200, [], true);
    }

    #[Route('/api/activity/{id}', name: 'api_activity_delete_activity', methods: ['DELETE'])]
    public function deleteActivity(string $id) : JsonResponse
    {
        $response = $this->activiteService->deleteActivity($id);
        $serializedResponse = $this->serializer->serialize($response, 'json', ['groups' => 'activite']);
        return new JsonResponse($serializedResponse, 200, [], true);
    }

    // gère la mise à jour d'une activity
    // prend un string en argument et retourne du JSON
    #[Route('/api/activity/{id}', name: 'api_activity_update_activity', methods: ['PUT'])]
    public function updateActivity(Request $request, string $id) : JsonResponse
    {
        $requestDatas = json_decode($request->getContent(), true);
        $activity = $this->activiteService->updateActivity($id, $requestDatas);
        $serializeResponse = $this->serializer->serialize($activity, 'json', ['groups' => 'activite']);
        return new JsonResponse($serializeResponse, 200, [], true);
    }

    #[Route('/api/activity', name: 'api_activity_get_activities', methods: ['GET'])]
    public function getActivities() : JsonResponse
    {
        $activities = $this->activiteService->getActivities();
        $serializedActivity = $this->serializer->serialize($activities, 'json', ['groups' => 'activite', 'artiste']);
        return new JsonResponse($serializedActivity, 200, [], true);
    }

    #[Route('/api/activity/{id}', name: 'api_activity_get_activity', methods:['GET'])]
    public function getActivity(string $id): JsonResponse
    {
        $activity = $this->activiteService->getActivity($id);
        $serializedActivity = $this->serializer->serialize($activity, 'json', ['groups' => 'activite', 'artiste']);
        return new JsonResponse($serializedActivity, 200, [], true);
    }

}