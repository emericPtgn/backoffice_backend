<?php

namespace App\Service;
use App\Document\Artiste;
use App\Document\Emplacement;
use App\Document\TypeActivite;
use DateTime;
use App\Document\Activite;
use Doctrine\ODM\MongoDB\DocumentManager;

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
            $date = new DateTime($requestDatas['date']);
            $activity->setDate($date);
        }
        if(isset($requestDatas['type'])){
            $activity->setType($requestDatas['type']);
        }
        if(isset($requestDatas['emplacement'])){
            if(isset($requestDatas['emplacement']['nom']))
            $emplacement = new Emplacement($requestDatas['emplacement']['nom'],$requestDatas['emplacement']['latitude'], $requestDatas['emplacement']['longitude']);
            $activity->setEmplacement($emplacement);
        }
        if(isset($requestDatas['typeActivite'])){
            $typeActivite = new TypeActivite();
            if(isset($requestDatas['typeActivite']['nom'])){
                $typeActivite->setNom($requestDatas['typeActivite']['nom']);
            }
            if(isset($requestDatas['typeActivite']['icone'])){
                $typeActivite->setIcone($requestDatas['typeActivite']['icone']);
            }
            $this->dm->persist($typeActivite);
            $activity->setTypeActivite($typeActivite);

        }
        if(isset($requestDatas['artiste'])){
            $artiste = new Artiste();
            if(isset($requestDatas['artiste']['nom'])){
                $artiste->setNom($requestDatas['artiste']['nom']);
            }
            if(isset($requestDatas['artiste']['description'])){
                $artiste->setDescription($requestDatas['artiste']['description']);
            }
            if(isset($requestDatas['artiste']['style'])){
                $artiste->setStyle($requestDatas['artiste']['style']);
            }
            $activity->setArtiste($artiste);
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
   