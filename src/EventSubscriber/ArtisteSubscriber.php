<?php

namespace App\EventSubscriber;

use App\Document\Activite;
use App\Document\Artiste;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Events;
use Doctrine\Common\EventSubscriber;

class ArtisteSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            Events::preRemove,
        ];
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $document = $args->getDocument();
        if (!$document instanceof Artiste) {
            return;
        }

        $dm = $args->getDocumentManager();
        $activiteRepo = $dm->getRepository(Activite::class);

        // Rechercher toutes les activités faisant référence à l'artiste supprimé
        $activites = $activiteRepo->findBy(['artistes' => $document]);

        foreach ($activites as $activite) {
            $activite->removeArtiste($document);
            $dm->persist($activite);
        }

        $dm->flush();
    }
}
