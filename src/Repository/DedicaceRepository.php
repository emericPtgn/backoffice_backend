<?php

namespace App\Repository;
use App\Document\Dedicace;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class DedicaceRepository extends DocumentRepository {
    
    public function __construct(DocumentManager $dm){
        $uow = $dm->getUnitOfWork();
        $classMetaData = $dm->getClassMetadata(Dedicace::class);
        parent::__construct($dm, $uow, $classMetaData);
    }
}