<?php

namespace App\Repository;
use App\Document\Activite;
use Psr\Log\LoggerInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class ActiviteRepository extends DocumentRepository {

    private ?LoggerInterface $logger = null;

    public function __construct(DocumentManager $dm)
    {
        $uow = $dm->getUnitOfWork();
        $classMetaData = $dm->getClassMetadata(Activite::class);
        parent::__construct($dm, $uow, $classMetaData);
    }
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
    private function log(string $level, string $message, array $context = []): void
    {
        if ($this->logger) {
            $this->logger->$level($message, $context);
        }
    }


}