<?php

namespace App\Service;
use App\Document\Commerce;
use Doctrine\ODM\MongoDB\DocumentManager;

class CommerceService {

    private DocumentManager $dm;
    public function __construct(DocumentManager $dm){
        $this->dm = $dm;

    }

    public function addNewCommerce(array $requestDatas){
        $newCommerce = new Commerce();
        if(isset($requestDatas['nom'])){
            $newCommerce->setNom($requestDatas['nom']);
        }
        if(isset($requestDatas['description'])){
            $newCommerce->setDescription($requestDatas['description']);
        }
        if(isset($requestDatas['emplacement'])){
            $newCommerce->setEmplacement($requestDatas['emplacement']);
        }
        if(isset($requestDatas['typeCommerce'])){
            $newCommerce->setTypeCommerce($requestDatas['typeCommerce']);
        }
        if(isset($requestDatas['reseauSocial'])){
            $newCommerce->setReseauSocial($requestDatas['reseauSocial']);
        }
        if(isset($requestDatas['typeProduit'])){
            $newCommerce->setTypeProduit($requestDatas['typeProduit']);
        }
        $this->dm->persist($newCommerce);
        $this->dm->flush();
        return $newCommerce;
    }

    public function updateCommerce(string $id, array $requestDatas){
        $commerceToUpdate = $this->dm->getRepository(Commerce::class)->find($id);
        if(!$commerceToUpdate){
            return ['message' => 'no commerce found with this id'];
        } else {
            if(isset($requestDatas['nom'])){
                $commerceToUpdate->setNom($requestDatas['nom']);
            }
            if(isset($requestDatas['description'])){
                $commerceToUpdate->setDescription($requestDatas['description']);
            }
            if(isset($requestDatas['emplacement'])){
                $commerceToUpdate->setEmplacement($requestDatas['emplacement']);
            }
            if(isset($requestDatas['typeCommerce'])){
                $commerceToUpdate->setTypeCommerce($requestDatas['typeCommerce']);
            }
            if(isset($requestDatas['reseauSocial'])){
                $commerceToUpdate->setReseauSocial($requestDatas['reseauSocial']);
            }
            if(isset($requestDatas['typeProduit'])){
                $commerceToUpdate->setTypeProduit($requestDatas['typeProduit']);
            }
            $updatedCommerce = $commerceToUpdate;
            $this->dm->persist($updatedCommerce);
            $this->dm->flush();
            return $updatedCommerce;
        }
    }

    public function getCommerce(string $id){
        $commerce = $this->dm->getRepository(Commerce::class)->find($id);
        if(!$commerce){
            return ['message' => 'no commerce found with this id'];
        } else {
            return $commerce;
        }
    }

    public function getAllCommerces(){
        $listeCommerces = $this->dm->getRepository(Commerce::class)->findAll();
        return $listeCommerces;
    }


}