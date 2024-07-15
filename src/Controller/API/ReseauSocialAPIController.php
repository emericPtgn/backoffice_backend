<?php

namespace App\Controller\API;
use App\Service\ReseauSocialService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class ReseauSocialAPIController {
    private ReseauSocialService $reseauSocialService;
    public function __construct(ReseauSocialService $reseauSocialService){
        $this->reseauSocialService = $reseauSocialService;
    }   

    #[Route('/api/social', name: 'api_social_new', methods: ['POST'])]
    public function addNewSocial(Request $request){
        $requestDatas = json_decode($request->getContent(), true);
        $newReseauSocial = $this->reseauSocialService->addNewSocial($requestDatas);
        return new JsonResponse($newReseauSocial, 200, [], false);
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
        return new JsonResponse($social, 200, [], false);
    }

    #[Route('/api/social', name: 'api_social_getAll', methods: ['GET'])]
    public function getAllSocials(string $id){
        $socials = $this->reseauSocialService->getAllSocials();
        return new JsonResponse($socials, 200, [], false);
    }

}