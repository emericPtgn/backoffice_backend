<?php

namespace App\Repository;
use App\Document\Emplacement;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class EmplacementRepository extends DocumentRepository {
    
    public function __construct(DocumentManager $dm){
        $uow = $dm->getUnitOfWork();
        $classMetaData = $dm->getClassMetadata(Emplacement::class);
        parent::__construct($dm, $uow, $classMetaData);
    }
}