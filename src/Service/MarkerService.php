<?php

namespace App\Service;
use App\Document\Emplacement;
use App\Document\Marker;
use App\Repository\MarkerRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Utils\DocumentUpdater;
use App\Utils\DocumentPersister;
use App\Utils\GenerateFieldsToMap;

class MarkerService {

    private DocumentManager $dm;
    private MarkerRepository $markerRepo;

    public function __construct(DocumentManager $dm, MarkerRepository $markerRepo){
        $this->dm = $dm;
        $this->markerRepo = $markerRepo;
    }

    public function addMarker(array $requestDatas)
{
    $newMarker = new Marker();

    // Liste des champs et leurs setters correspondants
    $fields = GenerateFieldsToMap::getPropertiesAndSetters($newMarker);

    DocumentUpdater::updateDocumentFields($newMarker, $requestDatas, $fields);
    return DocumentPersister::persistDocument($this->dm, $newMarker);
}


public function updateMarker(string $id, array $requestDatas) {
    $markerToUpdate = $this->markerRepo->find($id);

    $fields = GenerateFieldsToMap::getPropertiesAndSetters($markerToUpdate);

    if (!$markerToUpdate) {
        return [
            'message' => 'No marker found with this ID'
        ];
    }

    DocumentUpdater::updateDocumentFields($markerToUpdate, $requestDatas, $fields);
    return DocumentPersister::persistDocument($this->dm, $markerToUpdate);

}


    public function getMarker(string $id){
        $marker = $this->markerRepo->find($id);
        if(!$marker){
            return ['message' => 'no marker found with this ID'];
        } else {
            return $marker;
        }
    }

    public function getAllMarker(){
        $allMarkers = $this->markerRepo->findAll();
        if(!$allMarkers){
            return [
                'message' => 'no markers found, create a new one!',
                'status' => 'not found'
            ];
        } else {
            return [
                'status' => 'success',
                'data' => $allMarkers
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
    

    public function deleteMarker(string $id)
    {
        // Recherchez le marqueur par ID
        $markerToDelete = $this->markerRepo->find($id);
    
        if (!$markerToDelete) {
            // Retournez un message indiquant que le marqueur n'a pas été trouvé
            return [
                'message' => 'Marker not found',
                'status' => 'not found'
            ];
        }
    
        try {
            // Supprimez le marqueur de la base de données
            $this->dm->remove($markerToDelete);
            $this->dm->flush();
    
            // Retournez un message de succès
            return [
                'message' => 'Marker successfully deleted',
                'status' => 'success'
            ];
        } catch (\Exception $e) {
            // Gérez les exceptions potentielles
            return [
                'message' => 'An error occurred: ' . $e->getMessage(),
                'status' => 'error'
            ];
        }
    }
}