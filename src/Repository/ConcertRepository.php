<?php

namespace App\Repository;
use App\Document\Concert;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class ConcertRepository extends DocumentRepository {
    public function __construct(DocumentManager $dm)
    {
        $uow = $dm->getUnitOfWork();
        $classMetaData = $dm->getClassMetadata(Concert::class);
        parent::__construct($dm, $uow, $classMetaData);
    }
    
}