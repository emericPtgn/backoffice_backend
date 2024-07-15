<?php

namespace App\Repository;
use App\Document\Restauration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class RestaurationRepository extends DocumentRepository {
    public function __construct(DocumentManager $dm)
    {
        $uow = $dm->getUnitOfWork();
        $classMetaData = $dm->getClassMetadata(Restauration::class);
        parent::__construct($dm, $uow, $classMetaData);
    }
    
}