<?php

namespace App\Service;
use App\Document\TypeProduit;
use App\Repository\TypeProduitRepository;
use Doctrine\ODM\MongoDB\DocumentManager;

class TypeProduitService {
    private DocumentManager $dm;
    private TypeProduitRepository $typeProduitRepo;

    public function __construct(DocumentManager $dm, TypeProduitRepository $typeProduitRepo){
        $this->dm = $dm;
        $this->typeProduitRepo = $typeProduitRepo;
    }

    public function addNewType(array $requestDatas){
        $this->typeProduitRepo->addNewType($requestDatas);
    }

    public function updateTypeProduct(string $id, array $requestDatas){
        return $this->typeProduitRepo->updateTypeProduct($id, $requestDatas);
    }
    public function getTypeProduct(string $id){
        return $this->typeProduitRepo->getTypeProduct($id);
    }
    
    public function getAllTypesProducts(){
        return $this->typeProduitRepo->getAllTypesProducts();
    }

    public function removeTypeProduct(string $id){
        return $this->typeProduitRepo->removeTypeProduct($id);
    }
}