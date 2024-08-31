<?php

namespace App\EventSubscriber;

use App\Document\Marker;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Events;
use Doctrine\Common\EventSubscriber;
use App\Document\Commerce;

class MarkerSubscriber implements EventSubscriber {
    public function getSubscribedEvents(): array
    {
        return [
            Events::preRemove,
        ];
    }
    public function preRemove(LifecycleEventArgs $args){
        $document = $args->getDocument();
        if(!$document instanceof Marker){
            return;
        }
        $dm = $args->getDocumentManager();
        $commerceRepository = $dm->getRepository(Commerce::class);
        $commerce = $commerceRepository->findOneBy(['marker' => $document]);

        if($commerce){
            $commerce->setMarker(null);
            $dm->persist($commerce);
            $dm->flush();
        }

    }
} 