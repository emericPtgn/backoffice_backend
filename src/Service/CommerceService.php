<?php

namespace App\Service;
use App\Document\Commerce;
use App\Document\Emplacement;
use App\Document\TypeCommerce;
use App\Document\TypeProduit;
use App\Repository\CommerceRepository;
use Doctrine\ODM\MongoDB\DocumentManager;

class CommerceService {

    private DocumentManager $dm;
    private CommerceRepository $commerceRepo;
    public function __construct(DocumentManager $dm, CommerceRepository $commerceRepo){
        $this->dm = $dm;
        $this->commerceRepo = $commerceRepo;
    }

    public function addNewCommerce(array $requestDatas){
        return $this->commerceRepo->addNewCommerce($requestDatas);
    }

    public function updateCommerce(string $id, array $requestDatas){
            return $this->commerceRepo->updateCommerce($id, $requestDatas);
        }

    public function getCommerce(string $id){
        return $this->commerceRepo->getCommerce($id);
    }

    public function getAllCommerces(){
        return $this->commerceRepo->getAllCommerces();
    }
    
    public function deleteCommerce(string $id){
        return $this->commerceRepo->deleteCommerce($id);
    }


}