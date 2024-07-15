<?php

namespace App\Service;
use App\Document\Emplacement;
use Doctrine\ODM\MongoDB\DocumentManager;

class EmplacementService {

    private DocumentManager $dm;

    public function __construct(DocumentManager $dm){
        $this->dm = $dm;
    }

    public function addNewEmplacement(array $requestDatas){
        $newEmplacement = new Emplacement();
        if(isset($requestDatas['nom'])){
            $newEmplacement->setNom($requestDatas['nom']);
        }
        if(isset($requestDatas['lattitude'])){
            $newEmplacement->setLatitude($requestDatas['lattitude']);
        }
        if(isset($requestDatas['longitude'])){
            $newEmplacement->setLongitude($requestDatas['longitude']);
        }
        $this->dm->persist($newEmplacement);
        $this->dm->flush();
        return $newEmplacement;
    }

    public function updateEmplacement(string $id, array $requestDatas){
        $emplacementToUpdate = $this->dm->getRepository(Emplacement::class)->find($id);
        if(!$emplacementToUpdate){
            return ['message' => 'no emplacement found with this id'];
        }
        if(isset($requestDatas['nom'])){
            $emplacementToUpdate->setNom($requestDatas['nom']);
        }
        if(isset($requestDatas['lattitude'])){
            $emplacementToUpdate->setLatitude($requestDatas['lattitude']);
        }
        if(isset($requestDatas['longitude'])){
            $emplacementToUpdate->setLongitude($requestDatas['longitude']);
        }
        $this->dm->persist($emplacementToUpdate);
        $this->dm->flush();
        return $emplacementToUpdate;
    }

    public function getEmplacement(string $id){
        $emplacement = $this->dm->getRepository(Emplacement::class)->find($id);
        if(!$emplacement){
            return ['message' => 'no emplacement found with this ID'];
        } else {
            return $emplacement;
        }
    }

    public function getAllEmplacements(){
        $allEmplacements = $this->dm->getRepository(Emplacement::class)->findAll();
        if(!$allEmplacements){
            return ['message' => 'no emplacement found with this ID'];
        } else {
            return $allEmplacements;
        }
    }
}