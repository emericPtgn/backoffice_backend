<?php

namespace App\Controller\API;

use App\Service\AllDatasService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AllDatasController extends AbstractController {

    private Security $security;
    private AllDatasService $allDatasService;
    
    public function __construct(Security $security, AllDatasService $allDatasService)
    {
        $this->security = $security;
        $this->allDatasService = $allDatasService;
    }


    #[Route('/api/alldatas', name:'api_allDatas', methods: ['GET'])]
    function getAllDatas() : JsonResponse{
        $user = $this->security->getUser();
        $allDatas = $this->allDatasService->getAllDatas($user);
        return new JsonResponse($allDatas, 200, [], false);
    }
}