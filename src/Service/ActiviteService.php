<?php

namespace App\Service;
use App\Document\Artiste;
use App\Document\Emplacement;
use App\Document\TypeActivite;
use App\Repository\ActiviteRepository;
use DateTime;
use App\Document\Activite;
use Doctrine\ODM\MongoDB\DocumentManager;
use Psr\Log\LoggerInterface;

class ActiviteService {
    private DocumentManager $dm;
    private LoggerInterface $logger;
    private ActiviteRepository $activiteRepository;

    public function __construct(DocumentManager $dm, LoggerInterface $logger, ActiviteRepository $activiteRepository)
    {
        $this->dm = $dm;
        $this->logger = $logger;
        $this->activiteRepository = $activiteRepository;
    }   

    public function addActivity(array $requestDatas)
    {
       return $this->activiteRepository->addActivity($requestDatas);
    }


    // correspondance par id identifie l'activite à effacer de la base
    public function deleteActivity(string $id){
        return $this->activiteRepository->deleteActivity($id);
    }

    public function updateActivity(string $id, array $requestDatas)
    {
        return $this->activiteRepository->updateActivity($id, $requestDatas);
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
   