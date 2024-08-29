<?php

namespace App\Service;
use App\Document\Marker;
use App\Document\Commerce;
use App\Document\Emplacement;
use App\Document\TypeProduit;
use App\Document\TypeCommerce;
use App\Repository\CommerceRepository;
use Doctrine\ODM\MongoDB\DocumentManager;

class CommerceService {

    private DocumentManager $dm;
    private CommerceRepository $commerceRepo;

    private MarkerService $markerService;

    public function __construct(DocumentManager $dm, CommerceRepository $commerceRepo, MarkerService $markerService){
        $this->dm = $dm;
        $this->commerceRepo = $commerceRepo;
        $this->markerService = $markerService;
    }

    public function addNewCommerce(array $requestDatas) {
        // Traitement des données
        $newCommerce = new Commerce();
        $newMarker = new Marker();
    
        if (isset($requestDatas['nom'])) {
            $newCommerce->setNom($requestDatas['nom']);
            $newMarker->setNom($requestDatas['nom']);
        }
        if (isset($requestDatas['description'])) {
            $newCommerce->setDescription($requestDatas['description']);
            $newMarker->setDescription($requestDatas['description']);
        }
        if (isset($requestDatas['typeCommerce'])) {
            if (isset($requestDatas['typeCommerce']['id'])) {
                $typeCommerce = $this->dm->getRepository(TypeCommerce::class)->find($requestDatas['typeCommerce']['id']);
                $newCommerce->setTypeCommerce($typeCommerce);
                $newMarker->setType($typeCommerce->getNom());
            } else {
                $newTypeCommerce = new TypeCommerce();
                if (isset($requestDatas['typeCommerce']['nom'])) {
                    $newTypeCommerce->setNom($requestDatas['typeCommerce']['nom']);
                    $newMarker->setType($newTypeCommerce->getNom());
                }
                if (isset($requestDatas['typeCommerce']['icone'])) {
                    $newTypeCommerce->setIcone($requestDatas['typeCommerce']['icone']);
                }
                $this->dm->persist($newTypeCommerce);
                $newCommerce->setTypeCommerce($newTypeCommerce);

            }
        }
        if (isset($requestDatas['typeProduit'])) {
            if (isset($requestDatas['typeProduit']['id'])) {
                $typeProduit = $this->dm->getRepository(TypeProduit::class)->find($requestDatas['typeProduit']['id']);
                $newCommerce->setTypeProduit($typeProduit);
                $newMarker->setTypeProduit($typeProduit);
            } else {
                $newTypeProduit = new TypeProduit();
                if (isset($requestDatas['typeProduit']['nom'])) {
                    $newTypeProduit->setNom($requestDatas['typeProduit']['nom']);
                }
                if (isset($requestDatas['typeProduit']['icone'])) {
                    $newTypeProduit->setIcone($requestDatas['typeProduit']['icone']);
                    $newMarker->setIcone($requestDatas['typeProduit']['icone']);
                }
                $this->dm->persist($newTypeProduit);
                $newMarker->setTypeProduit($newTypeProduit);
                $newCommerce->setTypeProduit($newTypeProduit);
            }
        }
        if(isset($requestDatas['marker'])){
            if(isset($requestDatas['marker']['latitude'])){
                $newMarker->setLatitude($requestDatas['marker']['latitude']);
            }
            if(isset($requestDatas['marker']['longitude'])){
                $newMarker->setLongitude($requestDatas['marker']['longitude']);
            }
            if(isset($requestDatas['marker']['icone'])){
                $newMarker->setIcone($requestDatas['marker']['icone']);
            }
        }
        if (isset($requestDatas['photos']) && is_array($requestDatas['photos'])) {
            $newCommerce->setPhotos($requestDatas['photos']);
        }
        // Persistance des entités
        try {
            $this->dm->persist($newMarker);
            $this->dm->persist($newCommerce);
            $newCommerce->setMarker($newMarker);
            $this->dm->persist($newCommerce);
            $this->dm->flush();
            return [
                'status' => 'success',
                'data' => $newCommerce
            ];
        } catch (\Throwable $th) {
            return [
                'error' => $th,
                'status' => 'error'
            ];
        }

    }

    public function updateCommerce(string $id, array $requestDatas){
        $commerceToUpdate = $this->dm->getRepository(Commerce::class)->find($id);
        if(!$commerceToUpdate){
            return ['message' => 'no commerce found with this id'];
        } else {
            $markerToUpdate = $commerceToUpdate->getMarker();
            if(!$markerToUpdate){
                $markerToUpdate = new Marker();
            }
            if(isset($requestDatas['nom'])){
                $commerceToUpdate->setNom($requestDatas['nom']);
                $markerToUpdate->setNom($requestDatas['nom']);
            }
            if(isset($requestDatas['description'])){
                $commerceToUpdate->setDescription($requestDatas['description']);
                $markerToUpdate->setDescription($requestDatas['description']);
            }
            if(isset($requestDatas['reseauSocial'])){
                $commerceToUpdate->setReseauSocial($requestDatas['reseauSocial']);
            }
            if(isset($requestDatas['typeCommerce'])){
                $isId = array_key_exists('id', $requestDatas['typeCommerce']);
                $isNom = array_key_exists('nom', $requestDatas['typeCommerce']);
                if($isId){
                    $existIdTypeCommerce = $this->dm->getRepository(TypeCommerce::class)->find($requestDatas['typeCommerce']['id']);
                    $commerceToUpdate->setTypeCommerce($existIdTypeCommerce);
                };
                if($isNom){
                    $existNomTypeCommerce = $this->dm->getRepository(TypeCommerce::class)->findOneBy(['nom' => $requestDatas['typeCommerce']['nom']]);
                    if($existNomTypeCommerce){
                        $commerceToUpdate->setTypeCommerce($existNomTypeCommerce);
                    } else {
                        $newTypeCommerce = new TypeCommerce();
                        $newTypeCommerce->setNom($requestDatas['typeCommerce']['nom']);
                        $this->dm->persist($newTypeCommerce);
                        $commerceToUpdate->setTypeCommerce($newTypeCommerce);
                    }
                }
            }
            if(isset($requestDatas['typeProduit'])){
                $isId = array_key_exists('id', $requestDatas['typeProduit']);
                $isNom = array_key_exists('nom', $requestDatas['typeProduit']);
                $markerToUpdate;
                if($isId){
                    $existingType = $this->dm->getRepository(TypeProduit::class)->find($requestDatas['typeProduit']['id']);
                    $commerceToUpdate->setTypeProduit($existingType);
                    $markerToUpdate->setType($existingType->getNom());
                } elseif($isNom) {
                    $existingNomTypeProduit = $this->dm->getRepository(TypeProduit::class)->findOneBy(['nom' => $requestDatas['typeProduit']['nom']]);
                    if($existingNomTypeProduit){
                        $commerceToUpdate->setTypeProduit($existingNomTypeProduit);
                        $markerToUpdate->setType($existingNomTypeProduit->getNom());
                    } else {
                        $newTypeProduit = new TypeProduit();
                        $newTypeProduit->setNom($requestDatas['typeProduit']['nom']);
                        $this->dm->persist($newTypeProduit);
                        $commerceToUpdate->setTypeProduit($newTypeProduit);
                        $markerToUpdate->setType($newTypeProduit->getNom());
                    }
                }
            }
            if(isset($requestDatas['marker'])){
                if(isset($requestDatas['marker']['latitude'])){
                    $markerToUpdate->setLatitude($requestDatas['marker']['latitude']);
                }
                if(isset($requestDatas['marker']['longitude'])){
                    $markerToUpdate->setLongitude($requestDatas['marker']['longitude']);
                }
                if(isset($requestDatas['marker']['icone'])){
                    $markerToUpdate->setIcone($requestDatas['marker']['icone']);
                }
            }
            // Vider le tableau avant de le remplir avec de nouvelles valeurs
            $existingPhotos = [];
            // Vérifier et fusionner les nouvelles photos provenant des données de la requête
            if (isset($requestDatas['photos']) && is_array($requestDatas['photos'])) {
                $existingPhotos = array_merge($existingPhotos, $requestDatas['photos']);
            }
            if (isset($requestDatas['photosObj']) && is_array($requestDatas['photosObj'])) {
                $existingPhotos = array_merge($existingPhotos, $requestDatas['photosObj']);
            }
            // Mettre à jour l'objet avec les nouvelles photos
            $commerceToUpdate->setPhotos($existingPhotos);

            
            $this->dm->persist($markerToUpdate);
            $commerceToUpdate->setMarker($markerToUpdate);
            $this->dm->persist($commerceToUpdate);
            $this->dm->flush();
            return $commerceToUpdate;
        }
    }

    public function getCommerce(string $id){
        $commerce = $this->dm->getRepository(Commerce::class)->find($id);
        if(!$commerce){
            return ['message' => 'no commerce found with this id'];
        } else {
            return $commerce;
        }
    }

    public function getAllCommerces(){
        $listeCommerces = $this->dm->getRepository(Commerce::class)->findAll();
        return $listeCommerces;
    }
    
    public function deleteCommerce(string $id){
        $commerce = $this->commerceRepo->find($id);
        if(!$commerce){
            return [
                'message' => 'no commerce found',
                'status' => 'not found'
            ];
        }
        try {
            $marker = $commerce->getMarker();
            $this->markerService->deleteMarker($marker->getId());
            $this->dm->remove($commerce);
            $this->dm->flush();
            return [
                'message' => 'commerce removed successfully',
                'status' => 'success'
            ];
        } catch (\Exception $exception) {
            return [
                'message' => 'error during deletion : ' . $exception->getMessage(),
                'status' => 'error'
            ];
        }

    }


}