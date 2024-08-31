<?php

namespace App\Service;
use App\Document\TypeActivite;
use Doctrine\ODM\MongoDB\DocumentManager;

class TypeActiviteService {
    private DocumentManager $dm;
    public function __construct(DocumentManager $dm){
        $this->dm = $dm;
    }   
    public function addNewType(array $requestDatas){
        $newTypeActivity = new TypeActivite();
        if(isset($requestDatas['icone'])){
            $newTypeActivity->setIcone($requestDatas['icone']);
        }
        if(isset($requestDatas['nom'])){
            $newTypeActivity->setNom($requestDatas['nom']);
        }
        $this->dm->persist($newTypeActivity);
        $this->dm->flush();
        return $newTypeActivity;
    }

    public function updateTypeActivity(string $id, array $requestDatas){
        $activityToUpdate = $this->dm->getRepository(TypeActivite::class)->find($id);
        if(!$activityToUpdate){
            return ['message' => 'no activity found with this ID']; 
        } else {
            if(isset($requestDatas['icone'])){
                $activityToUpdate->setIcone($requestDatas['icone']);
            }
            if(isset($requestDatas['nom'])){
                $activityToUpdate->setNom($requestDatas['nom']);
            }
            $this->dm->persist($activityToUpdate);
            $this->dm->flush();
            return $activityToUpdate;
        }
    }

    public function getTypeActivity(string $id){
        $typeActivity = $this->dm->getRepository(TypeActivite::class)->find($id);
        if(!$typeActivity){
            return ['message' => 'no activity found with this ID'];
        } else {
            return $typeActivity;
        }
    }

    public function getAllTypesActivities(){
        $allTypesActivities = $this->dm->getRepository(TypeActivite::class)->findAll();
        if(!$allTypesActivities){
            return ['message' => 'no activity found with this ID'];
        } else {
            return $allTypesActivities;
        }
    }

    public function removeTypeActivity(string $id){
        $typeActivityToRemove = $this->dm->getRepository(TypeActivite::class)->find($id);
        if(!$typeActivityToRemove){
            return ['message' => 'no activity found with this ID'];
        } else {
            $this->dm->remove($typeActivityToRemove);
            $this->dm->flush();
            return ['message' => 'activity has been removed successfully'];
        }
    }

}