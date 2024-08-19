<?php

namespace App\Controller\API;

use Psr\Log\LoggerInterface;
use App\Service\CommerceService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CommerceAPIController extends AbstractController {

    private CommerceService $commerceService;
    private SerializerInterface $serializer;
    private LoggerInterface $logger;

    public function __construct(CommerceService $commerceService, SerializerInterface $serializer, LoggerInterface $logger){
        $this->commerceService = $commerceService;
        $this->serializer = $serializer;
        $this->logger = $logger;
    }

    #[Route('/api/commerce', name:'api_commerce_addCommerce', methods: ['POST'])]
    public function addCommerce(Request $request): JsonResponse {
        // Récupération des données textuelles (JSON)
        $data = $request->request->get('data');
        $decodedData = json_decode($data, true);
        
        // Vérification des fichiers
        $files = $request->files->get('photos');
    
        // Traitement des fichiers s'ils existent
        if ($files) {
            $uploadDirectory = $this->getParameter('kernel.project_dir') . '/public/uploads';
            $uploadedFilePaths = [];
            
            foreach ($files as $file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
    
                try {
                    $file->move($uploadDirectory, $newFilename);
                    $uploadedFilePaths[] = "/uploads/$newFilename";
                } catch (FileException $e) {
                    return new JsonResponse(['error' => "Failed to upload file: $originalFilename"], 500);
                }
            }
    
            // Met à jour le chemin des fichiers dans les données
            $decodedData['photos'] = $uploadedFilePaths;
        }
    
        // Passer les données décodées au service
        $newCommerce = $this->commerceService->addNewCommerce($decodedData);
        $serializedNewCommerce = $this->serializer->serialize($newCommerce, 'json', ['groups' => 'commerce']);
        return new JsonResponse($serializedNewCommerce, 200, [], true);
    }
    
    #[Route('/api/commerce/{id}', name:'api_commerce_updateCommerce', methods: ['POST'])]
    public function updateCommerce(string $id, Request $request): JsonResponse
    {
        $data = $request->request->get('data');
        $decodedData = json_decode($data, true);
        $this->logger->info('NON OBJECT', $decodedData);
        
        // Vérification des fichiers
        $files = $request->files->get('photos');
    
        // Traitement des fichiers s'ils existent
        if ($files) {
            $uploadDirectory = $this->getParameter('kernel.project_dir') . '/public/uploads';
            $uploadedFilePaths = [];
            
            foreach ($files as $file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
    
                try {
                    $file->move($uploadDirectory, $newFilename);
                    $uploadedFilePaths[] = "/uploads/$newFilename";
                } catch (FileException $e) {
                    return new JsonResponse(['error' => "Failed to upload file: $originalFilename"], 500);
                }
            }
            // Met à jour le chemin des fichiers dans les données

            $decodedData['photosObj'] = $uploadedFilePaths;
            $this->logger->info('PHOTOS OBJ', $decodedData['photosObj']);
        }
        $this->logger->info('Datas transmises à méthode UPDATE', $decodedData);
        // Passer les données décodées au service
        $updatedCommerce = $this->commerceService->updateCommerce($id, $decodedData);
        $serializedNewCommerce = $this->serializer->serialize($updatedCommerce, 'json', ['groups' => 'commerce']);
        return new JsonResponse($serializedNewCommerce, 200, [], true);
    }

    #[Route('/api/commerce/{id}', name:'api_commerce_getCommerce', methods: ['GET'])]
    public function getCommerce($id) : JsonResponse {
        $commerce = $this->commerceService->getCommerce($id);
        $serializedCommerce = $this->serializer->serialize($commerce, 'json', ['groups' => 'commerce']);
        return new JsonResponse($serializedCommerce, 200, [], true);
    }

    #[Route('/api/commerce', name:'api_commerce_getAllCommerces', methods: ['GET'])]
    public function getAllCommerces() : JsonResponse {
        $listCommerces = $this->commerceService->getAllCommerces();
        $serializedList = $this->serializer->serialize($listCommerces, 'json', ['groups' => 'commerce']);
        return new JsonResponse($serializedList, 200, [], true);
    }

    #[Route('/api/commerce/{id}', name:'api_commerce_deleteCommerce', methods: ['DELETE'])]
    public function deleteCommerce(string $id) : JsonResponse {
        $response = $this->commerceService->deleteCommerce($id);
        $serializedResponse = $this->serializer->serialize($response, 'json', ['groups' => 'commerce']);
        return new JsonResponse($serializedResponse, 200, [], true);
    }

}