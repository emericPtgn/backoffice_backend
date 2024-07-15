<?php

namespace App\Service;
use App\Document\Marker;
use Doctrine\ODM\MongoDB\DocumentManager;

class MarkerService {

    private DocumentManager $dm;
    public function __construct(DocumentManager $dm){
        $this->dm = $dm;
    }
    public function addMarker(array $requestDatas){
        $newMarker = new Marker();
        if(isset($requestDatas['description'])){
            $newMarker->setDescription($requestDatas['description']);
        }
        if(isset($requestDatas['nom'])){
            $newMarker->setNom($requestDatas['nom']);
        }
        if(isset($requestDatas['emplacement'])){
            $newMarker->setEmplacement($requestDatas['emplacement']);
        }
        if(isset($requestDatas['icone'])){
            $newMarker->setIcone($requestDatas['icone']);
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
            if(isset($requestDatas['emplacement'])){
                $markerToUpdate->setEmplacement($requestDatas['emplacement']);
            }
            if(isset($requestDatas['icone'])){
                $markerToUpdate->setIcone($requestDatas['icone']);
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
}