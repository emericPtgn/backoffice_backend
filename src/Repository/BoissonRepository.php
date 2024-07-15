<?php

namespace App\Repository;

use App\Document\Boisson;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class BoissonRepository extends DocumentRepository {
    public function __construct(DocumentManager $dm)
    {
        $uow = $dm->getUnitOfWork();
        $classMetaData = $dm->getClassMetadata(Boisson::class);
        parent::__construct($dm, $uow, $classMetaData);
    }
    
}