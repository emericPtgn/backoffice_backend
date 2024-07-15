<?php

namespace App\Repository;
use App\Document\Activite;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class ActiviteRepository extends DocumentRepository {
    public function __construct(DocumentManager $dm)
    {
        $uow = $dm->getUnitOfWork();
        $classMetaData = $dm->getClassMetadata(Activite::class);
        parent::__construct($dm, $uow, $classMetaData);
    }
    
}