<?php

namespace App\Repository;
use App\Document\Marker;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class MarkerRepository extends DocumentRepository {
    
    public function __construct(DocumentManager $dm){
        $uow = $dm->getUnitOfWork();
        $classMetaData = $dm->getClassMetadata(Marker::class);
        parent::__construct($dm, $uow, $classMetaData);
    }

    public function addMarker(array $requestDatas){
        $newMarker = new Marker();
        if(isset($requestDatas['description'])){
            $newMarker->setDescription($requestDatas['description']);
        }
        if(isset($requestDatas['nom'])){
            $newMarker->setNom($requestDatas['nom']);
        }
        if(isset($requestDatas['icone'])){
            $newMarker->setIcone($requestDatas['icone']);
        }
        if(isset($requestDatas['latitude']) && isset($requestDatas['longitude'])){
            $newMarker->setLatitude($requestDatas['latitude']);
            $newMarker->setLongitude($requestDatas['longitude']);
        }
        if(isset($requestDatas['type'])){
            $newMarker->setType($requestDatas['type']);
        }
        $this->dm->persist($newMarker);
        $this->dm->flush();
        return $newMarker;
    }

    public function updateMarker(string $id, array $requestDatas){
        $markerToUpdate = $this->dm->getRepository(Marker::class)->find($id);
        if(!$markerToUpdate){
            return ['message' => 'no marker found with this ID'];
        } else {
            if(isset($requestDatas['description'])){
                $markerToUpdate->setDescription($requestDatas['description']);
            }
            if(isset($requestDatas['nom'])){
                $markerToUpdate->setNom($requestDatas['nom']);
            }
            if(isset($requestDatas['icone'])){
                $markerToUpdate->setIcone($requestDatas['icone']);
            }
            if(isset($requestDatas['latitude']) && isset($requestDatas['longitude'])){
                $markerToUpdate->setLatitude($requestDatas['latitude']);
                $markerToUpdate->setLongitude($requestDatas['longitude']);
            }
            if(isset($requestDatas['type'])){
                $markerToUpdate->setType($requestDatas['type']);
            }
            $this->dm->persist($markerToUpdate);
            $this->dm->flush();
            return $markerToUpdate;
        }
    }

    public function getMarker(string $id){
        $marker = $this->dm->getRepository(Marker::class)->find($id);
        if(!$marker){
            return ['message' => 'no marker found with this ID'];
        } else {
            return $marker;
        }
    }

    public function getAllMarker(){
        $allMarkers = $this->dm->getRepository(Marker::class)->findAll();
        if(!$allMarkers){
            return ['message' => 'no markers found, create a new one!'];
        } else {
            return $allMarkers;
        }
    }

    public function deleteMarker(string $id)
    {
        // Recherchez le marqueur par ID
        $markerToDelete = $this->find($id);
    
        if (!$markerToDelete) {
            // Retournez un message indiquant que le marqueur n'a pas été trouvé
            return [
                'message' => 'Marker not found',
                'statut' => 'not found'
            ];
        }
    
        try {
            // Supprimez le marqueur de la base de données
            $this->dm->remove($markerToDelete);
            $this->dm->flush();
    
            // Retournez un message de succès
            return [
                'message' => 'Marker successfully deleted',
                'statut' => 'success'
            ];
        } catch (\Exception $e) {
            // Gérez les exceptions potentielles
            return [
                'message' => 'An error occurred: ' . $e->getMessage(),
                'statut' => 'error'
            ];
        }
    }
    

    public function addScene(array $requestDatas){
        $newScene = new Marker();
        if(isset($requestDatas['nom'])){
            $newScene->setNom($requestDatas['nom']);
        }
        if(isset($requestDatas['description'])){
            $newScene->setDescription($requestDatas['description']);
        }
        $newScene->setType('scene');
        $this->dm->persist($newScene);
        $this->dm->flush();
        return $newScene;
    }
}