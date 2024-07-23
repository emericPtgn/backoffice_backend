<?php

namespace App\Service;
use App\Document\Scene;
use App\Document\Emplacement;
use Doctrine\ODM\MongoDB\DocumentManager;

class SceneService {
    private DocumentManager $dm;
    public function __construct(DocumentManager $dm){
        $this->dm = $dm;
    }

    public function addScene(array $requestDatas){
        $newScene = new Scene();
        if(isset($requestDatas['nom'])){
            $newScene->setNom($requestDatas['nom']);
        }
        if(isset($requestDatas['emplacement'])){
            $emplacement = new Emplacement();
            if(isset($requestDatas['emplacement']['nom'])){
                $emplacement->setNom($requestDatas['emplacement']['nom']);
            }
            if(isset($requestDatas['emplacement']['latitude'])){
                $emplacement->setLatitude($requestDatas['emplacement']['latitude']);
            }
            if(isset($requestDatas['emplacement']['longitude'])){
                $emplacement->setLongitude($requestDatas['emplacement']['longitude']);
            }
            $this->dm->persist($emplacement);    
            $newScene->setEmplacement($emplacement);
        }
        $this->dm->persist($newScene);
        $this->dm->flush();
        return $newScene;
    }

    public function updateScene(string $id, array $requestDatas){
        $sceneToUpdate = $this->dm->getRepository(Scene::class)->find($id);
        if(!$sceneToUpdate){
            return ['message' => 'no scene found with this ID'];
        } else {
            if(isset($requestDatas['nom'])){
                $sceneToUpdate->setNom($requestDatas['nom']);
            }
            if(isset($requestDatas['emplacement'])){
                $sceneToUpdate->setEmplacement($requestDatas['emplacement']);
            }
            $this->dm->persist($sceneToUpdate);
            $this->dm->flush();
            return $sceneToUpdate;
        }
    }

    public function getScene(string $id){
        $scene = $this->dm->getRepository(Scene::class)->find($id);
        if(!$scene){
            return ['message' => 'no scene found with this ID'];
        } else {
            return $scene;
        }
    }

    public function getAllScenes(){
        $allScenes = $this->dm->getRepository(Scene::class)->findAll();
        if(!$allScenes){
            return ['message' => 'no scene found, create a new one!'];
        } else {
            return $allScenes;
        }
    }
}