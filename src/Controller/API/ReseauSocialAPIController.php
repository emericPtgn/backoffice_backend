<?php

namespace App\Controller\API;
use App\Service\ReseauSocialService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class ReseauSocialAPIController extends AbstractController {
    private ReseauSocialService $reseauSocialService;
    private SerializerInterface $serializer;
    public function __construct(ReseauSocialService $reseauSocialService, SerializerInterface $serializer){
        $this->reseauSocialService = $reseauSocialService;
        $this->serializer = $serializer;
    }   
    
    #[Route('/api/social', name: 'api_social_new', methods: ['POST'])]
    public function addNewSocial(Request $request){
        $requestDatas = json_decode($request->getContent(), true);
        $newReseauSocial = $this->reseauSocialService->addNewSocial($requestDatas);
        $serializedReseauSocial = $this->serializer->serialize($newReseauSocial, 'json');
        return new JsonResponse($serializedReseauSocial, 200, [], true);
    }

    #[Route('/api/social/{id}', name: 'api_social_update', methods: ['PUT'])]
    public function updateSocial(string $id, Request $request){
        $requestDatas = json_decode($request->getContent(), true);
        $updatedSocial = $this->reseauSocialService->updateSocial($id, $requestDatas);
        return new JsonResponse($updatedSocial, 200, [], false);
    }

    #[Route('/api/social/{id}', name: 'api_social_get', methods: ['GET'])]
    public function getSocial(string $id){
        $social = $this->reseauSocialService->getSocial($id);
        $serializedSocial = $this->serializer->serialize($social, 'json');
        return new JsonResponse($serializedSocial, 200, [], true);
    }

    #[Route('/api/social', name: 'api_social_getAll', methods: ['GET'])]
    public function getAllSocials(string $id){
        $socials = $this->reseauSocialService->getAllSocials();
        $serializedSocials = $this->serializer->serialize($socials, 'json');
        return new JsonResponse($serializedSocials, 200, [], false);
    }

}