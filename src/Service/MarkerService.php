<?php

namespace App\Service;
use App\Document\Emplacement;
use App\Document\Marker;
use App\Repository\MarkerRepository;
use Doctrine\ODM\MongoDB\DocumentManager;

class MarkerService {

    private DocumentManager $dm;
    private MarkerRepository $markerRepo;

    public function __construct(DocumentManager $dm, MarkerRepository $markerRepo){
        $this->dm = $dm;
        $this->markerRepo = $markerRepo;
    }
    public function addMarker(array $requestDatas){
        return $this->markerRepo->addMarker($requestDatas);
    }

    public function updateMarker(string $id, array $requestDatas){
        return $this->markerRepo->updateMarker($id, $requestDatas);
    }

    public function getMarker(string $id){
        return $this->markerRepo->getMarker($id);
    }

    public function getAllMarker(){
        return $this->markerRepo->getAllMarker();
    }

    public function addScene(array $requestDatas){
        return $this->markerRepo->addScene($requestDatas);
    }

    public function deleteMarker(string $id){
        return $this->markerRepo->deleteMarker($id);
    }
}