<?php

namespace App\Service;
use App\Document\Concert;
use Doctrine\ODM\MongoDB\DocumentManager;

class ConcertService {

    private DocumentManager $dm;
    public function __construct(DocumentManager $dm){
        $this->dm = $dm;
    }

    public function addNewConcert(array $requestDatas) : Concert
    {
        $newConcert = new Concert();
        if(isset($requestDatas['nom'])){
            $newConcert->setNom($requestDatas['nom']);
        }
        if(isset($requestDatas['type'])){
            $newConcert->setType($requestDatas['type']);
        }
        if(isset($requestDatas['artiste'])){
            $newConcert->setArtiste($requestDatas['artiste']);
        }
        if(isset($requestDatas['date'])){
            $newConcert->setDate($requestDatas['date']);
        }
        if(isset($requestDatas['emplacement'])){
            $newConcert->setEmplacement($requestDatas['emplacement']);
        };
        $this->dm->persist($newConcert);
        $this->dm->flush();
        return $newConcert;
    }

    public function updateConcert (string $id, array $requestDatas){
        $concertToUpdate = $this->dm->getRepository(Concert::class)->find($id);
        if(!$concertToUpdate){
            return ['message' => 'no concert found with this ID'];
        }
        if(isset($requestDatas['nom'])){
            $concertToUpdate->setNom($requestDatas['nom']);
        }
        if(isset($requestDatas['type'])){
            $concertToUpdate->setType($requestDatas['type']);
        }
        if(isset($requestDatas['artiste'])){
            $concertToUpdate->setArtiste($requestDatas['artiste']);
        }
        if(isset($requestDatas['date'])){
            $concertToUpdate->setDate($requestDatas['date']);
        }
        if(isset($requestDatas['emplacement'])){
            $concertToUpdate->setEmplacement($requestDatas['emplacement']);
        };
        $this->dm->persist($concertToUpdate);
        $this->dm->flush();
        return $concertToUpdate;
    }

    public function getConcert(string $id){
        $concert = $this->dm->getRepository(Concert::class)->find($id);
        if(!$concert){
            return ['message' => 'no concert found with this ID'];
        } else {
            return $concert;
        }
    }

    public function getAllConcerts(){
        $allConcerts = $this->dm->getRepository(Concert::class)->findAll();
        if(!$allConcerts){
            return ['message' => 'no concert found, create a new concert !'];
        } else {
            return $allConcerts;
        }
    } 

    public function removeConcert(string $id){
        $concert = $this->dm->getRepository(Concert::class)->find($id);
        if(!$concert){
            return ['message' => 'no concert found with this id'];
        } else {
            $this->dm->remove($concert);
            $this->dm->flush();
        }
    }
}