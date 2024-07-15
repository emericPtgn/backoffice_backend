<?php

namespace App\Repository;

use App\Document\Commerce;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class CommerceRepository extends DocumentRepository {
    public function __construct(DocumentManager $dm)
    {
        $uow = $dm->getUnitOfWork();
        $classMetaData = $dm->getClassMetadata(Commerce::class);
        parent::__construct($dm, $uow, $classMetaData);
    }
    
}