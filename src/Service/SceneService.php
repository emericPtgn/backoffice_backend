<?php

namespace App\Service;
use App\Document\Scene;
use App\Document\Emplacement;
use App\Repository\SceneRepository;
use Doctrine\ODM\MongoDB\DocumentManager;

class SceneService {
    private DocumentManager $dm;
    private SceneRepository $sceneRepository;
    public function __construct(DocumentManager $dm, SceneRepository $sceneRepository){
        $this->dm = $dm;
        $this->sceneRepository = $sceneRepository;
    }

    public function addScene(array $requestDatas){
        return $this->sceneRepository->addScene($requestDatas);
    }

    public function updateScene(string $id, array $requestDatas)
    {
        $sceneToUpdate = $this->dm->getRepository(Scene::class)->find($id);
        if (!$sceneToUpdate) {
            return ['message' => 'No scene found with this ID'];
        }
    
        if (isset($requestDatas['nom'])) {
            $sceneToUpdate->setNom($requestDatas['nom']);
        }
    
        if (isset($requestDatas['emplacement'])) {
            $emplacementData = $requestDatas['emplacement'];
            $emplacement = null;
    
            if (isset($emplacementData['id'])) {
                $emplacement = $this->dm->getRepository(Emplacement::class)->find($emplacementData['id']);
            }
    
            if ($emplacement) {
                $emplacement->setNom($emplacementData['nom']);
                $emplacement->setLatitude($emplacementData['latitude']);
                $emplacement->setLongitude($emplacementData['longitude']);
            } else {
                $emplacement = new Emplacement(
                    $emplacementData['nom'],
                    $emplacementData['latitude'],
                    $emplacementData['longitude']
                );
                $this->dm->persist($emplacement);
            }
    
            $sceneToUpdate->setEmplacement($emplacement);
        }
    
        $this->dm->persist($sceneToUpdate);
        $this->dm->flush();
    
        return $sceneToUpdate;
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