<?php

namespace App\Service;
use App\Document\TypeCommerce;
use App\Repository\TypeCommerceRepository;
use Doctrine\ODM\MongoDB\DocumentManager;

class TypeCommerceService {
    private DocumentManager $dm;
    private TypeCommerceRepository $typeCommerceRepo;

    public function __construct(DocumentManager $dm, TypeCommerceRepository $typeCommerceRepo){
        $this->dm = $dm;
        $this->typeCommerceRepo = $typeCommerceRepo;
    }   

    public function addNewType(array $requestDatas){
        return $this->typeCommerceRepo->addNewType($requestDatas);
    }

    public function updateTypeCommerce(string $id, array $requestDatas){
        return $this->typeCommerceRepo->updateTypeCommerce($id, $requestDatas);
    }

    public function getTypeCommerce(string $id){
        return $this->typeCommerceRepo->getTypeCommerce($id);
    }

    public function getAllTypesCommerces(){
        return $this->typeCommerceRepo->getAllTypesCommerces();
    }

    public function removeTypeCommerce(string $id){
        return $this->typeCommerceRepo->removeTypeCommerce($id);
    }

}