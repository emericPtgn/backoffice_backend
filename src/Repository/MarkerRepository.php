<?php

namespace App\Repository;
use App\Document\Marker;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class MarkerRepository extends DocumentRepository {
    
    public function __construct(DocumentManager $dm){
        $uow = $dm->getUnitOfWork();
        $classMetaData = $dm->getClassMetadata(Marker::class);
        parent::__construct($dm, $uow, $classMetaData);
    }

}