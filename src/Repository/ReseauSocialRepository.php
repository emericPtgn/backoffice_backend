<?php

namespace App\Repository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;
use App\Document\ReseauSocial;

class ReseauSocialRepository extends DocumentRepository {
    public function __construct(DocumentManager $dm)
    {
        $uow = $dm->getUnitOfWork();
        $classMetaData = $dm->getClassMetadata(ReseauSocial::class);
        parent::__construct($dm, $uow, $classMetaData);
    }


    
}