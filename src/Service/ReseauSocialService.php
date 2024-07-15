<?php

namespace App\Service;
use App\Document\ReseauSocial;
use Doctrine\ODM\MongoDB\DocumentManager;

class ReseauSocialService {
    private DocumentManager $dm;
    public function __construct(DocumentManager $dm){
        $this->dm = $dm;
    }
    public function addNewSocial(array $requestDatas){
        $newReseauSocial = new ReseauSocial();
        if(isset($requestDatas['plateforme'])){
            $newReseauSocial->setPlateforme($requestDatas['plateforme']);
        }
        if(isset($requestDatas['pseudo'])){
            $newReseauSocial->setPseudo($requestDatas['pseudo']);
        }
        if(isset($requestDatas['url'])){
            $newReseauSocial->setUrl($requestDatas['url']);
        }
        if(isset($requestDatas['icone'])){
            $newReseauSocial->setIcone($requestDatas['icone']);
        }
        $this->dm->persist($newReseauSocial);
        $this->dm->flush();
        return $newReseauSocial;
    }

    public function updateSocial(string $id, array $requestDatas){
        $social = $this->dm->getRepository(ReseauSocial::class)->find($id);
        if(!$social){
            return ['message' => 'no social account found with this ID'];
        } else {
            if(isset($requestDatas['plateforme'])){
                $social->setPlateforme($requestDatas['plateforme']);
            }
            if(isset($requestDatas['pseudo'])){
                $social->setPseudo($requestDatas['pseudo']);
            }
            if(isset($requestDatas['url'])){
                $social->setUrl($requestDatas['url']);
            }
            if(isset($requestDatas['icone'])){
                $social->setIcone($requestDatas['icone']);
            }
            $this->dm->persist($social);
            $this->dm->flush();
        }
    }

    public function getSocial(string $id){
        $social = $this->dm->getRepository(ReseauSocial::class)->find($id);
        if(!$social){
            return ['message' => 'no social account found with this ID']; 
        } else {
            return $social;
        }
    }

    public function getAllSocials(){
        $allSocials = $this->dm->getRepository(ReseauSocial::class)->findAll();
        if(!$allSocials){
            return ['message' => 'no socials found, create a new one!'];
        }
        return $allSocials;
    }
}