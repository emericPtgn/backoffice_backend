<?php

namespace App\Repository;

use App\Document\Marker;
use App\Document\Artiste;
use App\Document\Activite;
use Error;
use Psr\Log\LoggerInterface;
use App\Document\ReseauSocial;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class ArtisteRepository extends DocumentRepository
{
    private ?LoggerInterface $logger = null;

    public function __construct(DocumentManager $dm)
    {
        $classMetaData = $dm->getClassMetadata(Artiste::class);
        parent::__construct($dm, $dm->getUnitOfWork(), $classMetaData);
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
