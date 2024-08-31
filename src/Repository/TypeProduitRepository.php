<?php

namespace App\Repository;


use App\Document\Commerce;
use App\Document\Emplacement;
use App\Document\TypeProduit;
use App\Document\TypeCommerce;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class TypeProduitRepository extends DocumentRepository {
    
    public function __construct(DocumentManager $dm)
    {
        $uow = $dm->getUnitOfWork();
        $classMetaData = $dm->getClassMetadata(TypeProduit::class);
        parent::__construct($dm, $uow, $classMetaData);
    }

    public function addNewType(array $requestDatas){
        $newTypeProduct = new TypeProduit();
        if(isset($requestDatas['icone'])){
            $newTypeProduct->setIcone($requestDatas['icone']);
        }
        if(isset($requestDatas['nom'])){
            $newTypeProduct->setNom($requestDatas['nom']);
        }
        $this->dm->persist($newTypeProduct);
        $this->dm->flush();
        return $newTypeProduct;
    }

    public function updateTypeProduct(string $id, array $requestDatas){
        $productToUpdate = $this->dm->getRepository(TypeProduit::class)->find($id);
        if(!$productToUpdate){
            return ['message' => 'no product found with this ID']; 
        } else {
            if(isset($requestDatas['icone'])){
                $productToUpdate->setIcone($requestDatas['icone']);
            }
            if(isset($requestDatas['nom'])){
                $productToUpdate->setNom($requestDatas['nom']);
            }
            $this->dm->persist($productToUpdate);
            $this->dm->flush();
            return $productToUpdate;
        }
    }

    public function getTypeProduct(string $id){
        $typeProduct = $this->dm->getRepository(TypeProduit::class)->find($id);
        if(!$typeProduct){
            return ['message' => 'no product found with this ID'];
        } else {
            return $typeProduct;
        }
    }

    public function getAllTypesProducts(){
        $allTypesProducts = $this->dm->getRepository(TypeProduit::class)->findAll();
        if(!$allTypesProducts){
            return ['message' => 'no product found with this ID'];
        } else {
            return $allTypesProducts;
        }
    }
    public function removeTypeProduct(string $id){
        $typeProductToRemove = $this->dm->getRepository(TypeProduit::class)->find($id);
        if(!$typeProductToRemove){
            return ['message' => 'no product found with this ID'];
        } else {
            $this->dm->remove($typeProductToRemove);
            $this->dm->flush();
            return ['message' => 'product has been removed successfully'];
        }
    }
}