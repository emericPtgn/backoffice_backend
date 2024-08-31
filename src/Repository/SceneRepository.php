<?php

namespace App\Repository;
use App\Document\Scene;
use App\Document\Emplacement;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class SceneRepository extends DocumentRepository {
    public function __construct(DocumentManager $dm)
    {
        $uow = $dm->getUnitOfWork();
        $classMetaData = $dm->getClassMetadata(Scene::class);
        parent::__construct($dm, $uow, $classMetaData);
    }
    
    public function addScene(array $requestDatas, bool $confirm = false){
        // vérifier si la scene avec le même nom existe déjà, demander validation user avant de confirmer création nouvelle scene avec le même nom
        if(isset($requestDatas['nom'])){
            if($isExistingScene = $this->findOneBy(['nom' => $requestDatas['nom']])){
                if(!$confirm){
                    return [
                        'message' => 'A scene already exist with the same name:'.$isExistingScene->getNom().', create a new one with same name ?',
                        'needsConfirmation' => true
                    ];
                }
            }
        };
        // si confirmation est donnée de créer une nouvelle scène
        $newScene = new Scene();
        if (isset($requestDatas['nom'])) {
            $newScene->setNom($requestDatas['nom']);
        }
        if(isset($requestDatas['emplacement'])){
            if($isExistingEmplacement = $this->dm->getRepository(Emplacement::class)->findOneBy(['name' => $requestDatas['emplacement']['nom']])){
                $newScene->setEmplacement($isExistingEmplacement);
            }
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

}