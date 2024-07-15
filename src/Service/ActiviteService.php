<?php

namespace App\Service;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Document\Activite;

class ActiviteService {
    private DocumentManager $dm;
    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    public function addActivity(array $requestDatas)
    {
        $activity = new Activite();
        if(isset($requestDatas['nom'])){
            $activity->setNom($requestDatas['nom']);
        }
        if(isset($requestDatas['date'])){
            $activity->setDate($requestDatas['date']);
        }
        if(isset($requestDatas['type'])){
            $activity->setType($requestDatas['type']);
        }
        if(isset($requestDatas['emplacement'])){
            $activity->setEmplacement($requestDatas['emplacement']);
        }
        $this->dm->persist($activity);
        $this->dm->flush();
        return $activity;
    }


    // correspondance par id identifie l'activite à effacer de la base
    public function removeActivity(string $id){
        $activity = $this->dm->getRepository(Activite::class)->find($id);
        if(!$activity){
            return ['mesage' => 'no activity found'];
        } else {
            $this->dm->remove($activity);
            return ['message' => 'activity removed successfully'];
        }
    }

    public function updateActivity(string $id, array $requestDatas){
        $activity = $this->dm->getRepository(Activite::class)->find($id);
        if(!$activity){
            return ['message' => 'activity not found'];
        } else {
            if(isset($requestDatas['nom'])){
                $activity->setNom($requestDatas['nom']);
            }
            if(isset($requestDatas['date'])){
                $activity->setDate($requestDatas['date']);
            }
            if(isset($requestDatas['type'])){
                $activity->setType($requestDatas['type']);
            }
            if(isset($requestDatas['emplacement'])){
                $activity->setEmplacement($requestDatas['emplacement']);
            }
        }
    }

    public function getActivities(){
        $activities = $this->dm->getRepository(Activite::class)->findAll();
        if(!$activities){
            return ['message' => 'no activity found'];
        } else {
            return $activities;
        }
    }

    public function getActivity(string $id){
        $activity = $this->dm->getRepository(Activite::class)->find($id);
        if(!$activity){
            return ['message' => 'no activity found'];
        } else {
            return $activity;
        }
    }



}
   