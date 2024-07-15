<?php

namespace App\Repository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;
use App\Document\Scene;

class SceneRepository extends DocumentRepository {
    public function __construct(DocumentManager $dm)
    {
        $uow = $dm->getUnitOfWork();
        $classMetaData = $dm->getClassMetadata(Scene::class);
        parent::__construct($dm, $uow, $classMetaData);
    }
    
}