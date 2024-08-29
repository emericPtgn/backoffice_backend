<?php

namespace App\Controller\API;

use App\Service\ProgrammationService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[AsController]
class ProgrammationAPIController extends AbstractController {

    private ProgrammationService $programmationService;
    private SerializerInterface $serializer;

    public function __construct(ProgrammationService $programmationService, SerializerInterface $serializer){
        $this->programmationService = $programmationService;
        $this->serializer = $serializer;
    }

    #[Route('/api/programmation', name: 'api_programmation_addnew', methods:['POST'])]
    public function addProgrammation(Request $request) : JsonResponse {
        $requestDatas = json_decode($request->getContent(), true);
        $programmation = $this->programmationService->addProgrammation($requestDatas);
        $serializedProgrammation = $this->serializer->serialize($programmation, 'json', ['groups' => 'programmation']);
        return new JsonResponse($serializedProgrammation, 200, [], true);
    }

    #[Route('/api/programmation/{id}', name: 'api_programmation_delete', methods:['DELETE'])]
    public function deleteProgrammation(string $id) : JsonResponse {
        $response = $this->programmationService->deleteProgrammation($id);
        $serializedResponse = $this->serializer->serialize($response, 'json', ['groups' => 'programmation']);
        return new JsonResponse($serializedResponse, 200, [], true);
    }

    #[Route('/api/programmation/{id}', name: 'api_programmation_update', methods:['PUT'])]
    public function updateProgrammation(Request $request, string $id) : JsonResponse {
        $requestDatas = json_decode($request->getContent(), true);
        $response = $this->programmationService->updateProgrammation($id, $requestDatas);
        $serializedResponse = $this->serializer->serialize($response, 'json', ['groups' => 'programmation']);
        return new JsonResponse($serializedResponse, 200, [], true);
    } 

    #[Route('/public/api/programmation/{id}', name: 'api_programmation_get', methods:['GET'])]
    public function getProgrammation(string $id) : JsonResponse {
        $response = $this->programmationService->getProgrammation($id);
        $serializedResponse = $this->serializer->serialize($response, 'json', ['groups' => 'programmation']);
        return new JsonResponse($serializedResponse, 200, [], true);
    }

    #[Route('/public/api/programmation', name: 'api_programmation_get_liste', methods:['GET'])]
    public function getAllProgrammations() : JsonResponse {
        $response = $this->programmationService->getAllProgrammations();
        $serializedResponse = $this->serializer->serialize($response, 'json', ['groups' => 'programmation']);
        return new JsonResponse($serializedResponse, 200, [], true);
    }


}