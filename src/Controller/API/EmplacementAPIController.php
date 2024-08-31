<?php

namespace App\Controller\API;
use App\Service\EmplacementService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class EmplacementAPIController extends AbstractController{

    private EmplacementService $emplacementService;
    private SerializerInterface $serializer;

    public function __construct(EmplacementService $emplacementService, SerializerInterface $serializer){
        $this->emplacementService = $emplacementService;
        $this->serializer = $serializer;
    }

    #[Route('/api/emplacement', name: 'api_emplacement_addNew', methods: ['POST'])]
    public function addEmplacement(Request $request){
        $requestDatas = json_decode($request->getContent(), true);
        $newEmplacement = $this->emplacementService->addNewEmplacement($requestDatas);
        $serializedEmplacement = $this->serializer->serialize($newEmplacement, 'json', ['groups' => 'emplacement']);
        return new JsonResponse($serializedEmplacement, 200, [], true);
    }

    #[Route('/api/emplacement/{id}', name: 'api_emplacement_update', methods: ['PUT'])]
    public function updateEmplacement(string $id, Request $request){
        $requestDatas = json_decode($request->getContent(), true);
        $updatedEmplacement = $this->emplacementService->updateEmplacement($id, $requestDatas);
        return new JsonResponse($updatedEmplacement, 200, [], false);
    }

    #[Route('/public/api/emplacement/{id}', name: 'api_emplacement_get', methods: ['GET'])]
    public function getEmplacement(string $id){
        $emplacement = $this->emplacementService->getEmplacement($id);
        $serializedEmplacement = $this->serializer->serialize($emplacement, 'json', ['groups' => 'emplacement']);
        return new JsonResponse($serializedEmplacement, 200, [], true);
    }

    #[Route('/public/api/emplacement', name: 'api_emplacement_getAll', methods: ['GET'])]
    public function getAllEmplacements(){
        $allEmplacements = $this->emplacementService->getAllEmplacements();
        $serializedEmplacement = $this->serializer->serialize($allEmplacements, 'json', ['groups' => 'emplacement']);
        return new JsonResponse($serializedEmplacement, 200, [], true);
    }

}