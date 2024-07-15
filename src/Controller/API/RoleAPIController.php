<?php

namespace App\Controller\API;

use App\Security\Roles;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RoleAPIController extends AbstractController
{
    #[Route('/api/roles', name: 'api_get_roles', methods: ['GET'])]
    public function getRoles(): JsonResponse
    {
        $roles = Roles::getAvailableRoles();
        return new JsonResponse($roles);
    }
}
