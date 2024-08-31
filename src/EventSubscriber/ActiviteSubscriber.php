<?php

namespace App\EventSubscriber;

use App\Document\Activite;
use App\Document\Artiste;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Events;
use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\DocumentManager;
use Psr\Log\LoggerInterface;

class ActiviteSubscriber implements EventSubscriber
{
    private $logger;
    private $dm;

    public function __construct(LoggerInterface $logger, DocumentManager $dm)
    {
        $this->logger = $logger;
        $this->dm = $dm;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postRemove,
        ];
    }

    public function postRemove(LifecycleEventArgs $args): void
    {
        $this->logger->info('ActiviteSubscriber postRemove called for Activite: ');
    }

}