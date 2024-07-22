<?php

namespace App\Service;
use App\Document\Commerce;
use App\Document\Emplacement;
use App\Document\TypeCommerce;
use App\Document\TypeProduit;
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
            $nom = $requestDatas['emplacement']['nom'];
            $lat = $requestDatas['emplacement']['latitude'];
            $long = $requestDatas['emplacement']['longitude'];
            $emplacement = new Emplacement($nom, $lat, $long);
            $newCommerce->setEmplacement($emplacement);

        }
        if(isset($requestDatas['typeCommerce'])){
            if(isset($requestDatas['typeCommerce']['id'])){
                $typeCommerce = $this->dm->getRepository(TypeCommerce::class)->find(['typeCommerce']['id']);
                $newCommerce->setTypeCommerce($typeCommerce);
            } else {
                $newTypeCommerce = new TypeCommerce();
                if(isset($requestDatas['typeCommerce']['nom'])){
                    $newTypeCommerce->setNom($requestDatas['typeCommerce']['nom']);
                }
                if(isset($requestDatas['typeCommerce']['icone'])){
                    $newTypeCommerce->setIcone($requestDatas['typeCommerce']['icone']);
                }
                $this->dm->persist($newTypeCommerce);
                $newCommerce->setTypeCommerce($newTypeCommerce);
            }            
        }
        if(isset($requestDatas['reseauSocial'])){
            $newCommerce->setReseauSocial($requestDatas['reseauSocial']);
        }
        if(isset($requestDatas['typeProduit'])){
            if(isset($requestDatas['typeProduit']['id'])){
                $typeProduit = $this->dm->getRepository(TypeProduit::class)->find(['typeProduit']['id']);
                $newCommerce->setTypeProduit($typeProduit);
            } else {
                $newTypeProduit = new TypeProduit();
                if(isset($requestDatas['typeProduit']['nom'])){
                    $newTypeProduit->setNom($requestDatas['typeProduit']['nom']);
                }
                if(isset($requestDatas['typeProduit']['icone'])){
                    $newTypeProduit->setIcone($requestDatas['typeProduit']['icone']);
                }
                $this->dm->persist($newTypeProduit);
                $newCommerce->setTypeProduit($newTypeProduit);
            }            
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