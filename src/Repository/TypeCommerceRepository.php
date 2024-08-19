<?php

namespace App\Repository;

use App\Document\Commerce;
use App\Document\Emplacement;
use App\Document\TypeProduit;
use App\Document\TypeCommerce;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class TypeCommerceRepository extends DocumentRepository {
    public function __construct(DocumentManager $dm)
    {
        $uow = $dm->getUnitOfWork();
        $classMetaData = $dm->getClassMetadata(TypeCommerce::class);
        parent::__construct($dm, $uow, $classMetaData);
    }


    public function addNewType(array $requestDatas){
        $newTypeCommerce = new TypeCommerce();
        if(isset($requestDatas['icone'])){
            $newTypeCommerce->setIcone($requestDatas['icone']);
        }
        if(isset($requestDatas['nom'])){
            $newTypeCommerce->setNom($requestDatas['nom']);
        }
        $this->dm->persist($newTypeCommerce);
        $this->dm->flush();
        return $newTypeCommerce;
    }

    public function updateTypeCommerce(string $id, array $requestDatas){
        $commerceToUpdate = $this->dm->getRepository(TypeCommerce::class)->find($id);
        if(!$commerceToUpdate){
            return ['message' => 'no commerce found with this ID']; 
        } else {
            if(isset($requestDatas['icone'])){
                $commerceToUpdate->setIcone($requestDatas['icone']);
            }
            if(isset($requestDatas['nom'])){
                $commerceToUpdate->setNom($requestDatas['nom']);
            }
            $this->dm->persist($commerceToUpdate);
            $this->dm->flush();
            return $commerceToUpdate;
        }
    }

    public function getTypeCommerce(string $id){
        $typeCommerce = $this->dm->getRepository(TypeCommerce::class)->find($id);
        if(!$typeCommerce){
            return ['message' => 'no commerce found with this ID'];
        } else {
            return $typeCommerce;
        }
    }

    public function getAllTypesCommerces(){
        $allTypesCommerces = $this->dm->getRepository(TypeCommerce::class)->findAll();
        if(!$allTypesCommerces){
            return ['message' => 'no commerce found with this ID'];
        } else {
            return $allTypesCommerces;
        }
    }

    public function removeTypeCommerce(string $id){
        $typeCommerceToRemove = $this->dm->getRepository(TypeCommerce::class)->find($id);
        if(!$typeCommerceToRemove){
            return ['message' => 'no commerce found with this ID'];
        } else {
            $this->dm->remove($typeCommerceToRemove);
            $this->dm->flush();
            return ['message' => 'commerce has been removed successfully'];
        }
    }
}