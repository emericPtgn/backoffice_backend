<?php

namespace App\Repository;
use App\Document\Artiste;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class ArtisteRepository extends DocumentRepository {
    public function __construct(DocumentManager $dm)
    {
        $uow = $dm->getUnitOfWork();
        $classMetaData = $dm->getClassMetadata(Artiste::class);
        parent::__construct($dm, $uow, $classMetaData);
    }
    
}