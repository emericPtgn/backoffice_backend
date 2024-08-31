<?php

namespace App\Service;
use DateTime;
use App\Document\Marker;
use App\Document\Artiste;
use App\Document\Activite;
use Psr\Log\LoggerInterface;
use App\Repository\ActiviteRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Utils\DocumentPersister;

class ActiviteService {
    private DocumentManager $dm;
    private LoggerInterface $logger;
    private ActiviteRepository $activiteRepository;

    public function __construct(DocumentManager $dm, LoggerInterface $logger, ActiviteRepository $activiteRepository)
    {
        $this->dm = $dm;
        $this->logger = $logger;
        $this->activiteRepository = $activiteRepository;
    }   

public function addActivity(array $requestDatas)
{
    $activity = new Activite();

    if (isset($requestDatas['nom'])) {
        $existingActivity = $this->dm->getRepository(Activite::class)->findOneBy(['nom' => $requestDatas['nom']]);
        if ($existingActivity) {
            return ['message' => 'Une activité existe avec un nom identique'];
        } else {
            $activity->setNom($requestDatas['nom']);
        }
    }

    if (isset($requestDatas['date'])) {
        try {
            $dateTime = new DateTime($requestDatas['date']);
            $activity->setDate($dateTime);
        } catch (\Exception $e) {
            return ['message' => 'Invalid date format. Please use yyyy-MM-ddThh:mm'];
        }
    }

    if (isset($requestDatas['type'])) {
        $activity->setType($requestDatas['type']);
    }

    if (isset($requestDatas['location'])) {
        $existingMarker = $this->dm->getRepository(Marker::class)->findOneBy(['nom' => $requestDatas['location']]);
        if ($existingMarker) {
            $activity->setMarker($existingMarker);
        } else {
            return ['message' => 'Marker not found with the provided ID'];
        }
    }

    // if (isset($requestDatas['artiste'])) {
    //     $artisteData = $requestDatas['artiste'];

    //     $existingArtiste = $this->dm->getRepository(Artiste::class)->findOneBy(['nom' => $artisteData['nom']]);
    //     if ($existingArtiste) {
    //         $activity->addArtiste($existingArtiste);
    //         $existingArtiste->addActivite($activity); // Ajoutez cette ligne
    //     } else {
    //         $newArtiste = new Artiste();
    //         if (isset($artisteData['nom'])) {
    //             $newArtiste->setNom($artisteData['nom']);
    //         }
    //         if (isset($artisteData['description'])) {
    //             $newArtiste->setDescription($artisteData['description']);
    //         }
    //         if (isset($artisteData['style']) && is_array($artisteData['style'])) {
    //             $newArtiste->setStyles($artisteData['style']);
    //         }
    //         $this->dm->persist($newArtiste);
    //         $activity->addArtiste($newArtiste);
    //         $newArtiste->addActivite($activity); // Ajoutez cette ligne
    //     }
    // }
    
    if (isset($requestDatas['description'])) {
        $activity->setDescription($requestDatas['description']);
    }
    return DocumentPersister::persistDocument($this->dm, $activity);
}

public function deleteActivity(string $id)
{
    // Trouver l'activité à supprimer
    $activity = $this->dm->getRepository(Activite::class)->find($id);
    
    if (!$activity) {
        return [
            'message' => 'No activity found',
            'status' => 'error'
        ];
    }

    try {
        // Trouver tous les artistes associés à l'activité
        $artistes = $this->dm->getRepository(Artiste::class)->findBy(['activities' => $activity]);
        
        foreach ($artistes as $artiste) {
            $artiste->removeActivite($activity);
        }

        // Enregistrer les modifications aux artistes
        foreach ($artistes as $artiste) {
            $this->dm->persist($artiste);
        }

        // Supprimer l'activité
        $this->dm->remove($activity);
        $this->dm->flush();

        return [
            'message' => 'Activity removed successfully',
            'status' => 'success'
        ];
    } catch (\Exception $exception) {
        // Log l'erreur pour le débogage
        $this->logger->error('An error occurred while deleting activity: ' . $exception->getMessage());

        return [
            'message' => 'An error occurred: ' . $exception->getMessage(),
            'status' => 'error'
        ];
    }
}


    public function updateActivity(string $id, array $requestDatas)
    {
        try {
    
            $activity = $this->dm->getRepository(Activite::class)->find($id);
            if (!$activity) {
                return ['message' => 'activity not found'];
            }
    
            if (isset($requestDatas['nom'])) {
                $activity->setNom($requestDatas['nom']);
            }
    
            if (isset($requestDatas['date'])) {
                $date = new DateTime($requestDatas['date']);
                if ($date === false) {
                    return ['message' => 'Invalid date format. Please use yyyy-MM-ddThh:mm'];
                }
                $activity->setDate($date);
            }
    
            if (isset($requestDatas['type'])) {
                $activity->setType($requestDatas['type']);
            }
    
            if (isset($requestDatas['description'])) {
                $activity->setDescription($requestDatas['description']);
            }
    
            if(isset($requestDatas['artiste'])){
                // verifie si artisteID est un tableau et contient clé id
                // si oui initialise artisteId avec valeur clé id
                // sinon initialise avec valeur unique 
                $artisteId = (is_array($requestDatas['artiste']) && isset($requestDatas['artiste']['id'])) ? 
                $requestDatas['artiste']['id'] : $requestDatas['artiste'];
                
                $isExistingArtiste = $this->dm->getRepository(Artiste::class)->find($artisteId);
            
                if($isExistingArtiste){
                    $activity->addArtiste($isExistingArtiste);
                } else {            
                    $newArtiste = new Artiste();
                    $newArtiste->setNom($requestDatas['artiste']['nom'] ?? $requestDatas['artiste']);
                    $this->dm->persist($newArtiste);
                    $activity->addArtiste($newArtiste);
                    
                }
            }
    
            if(isset($requestDatas['location'])){
                $location = $this->dm->getRepository(Marker::class)->findOneBy(['nom' => $requestDatas['location']]);
                $activity->setMarker($location);
            }
    
            return DocumentPersister::persistDocument($this->dm, $activity);
        } catch (\Exception $e) {
            return ['message' => 'Une erreur est survenue lors de la mise à jour de l\'activité: ' . $e->getMessage()];
        }
    }

    public function getActivities(){
        $activities = $this->dm->getRepository(Activite::class)->findAll();
        if(!$activities){
            return ['message' => 'no activity found'];
        } else {
            return $activities;
        }
    }

    public function getActivity(string $id)
    {
        $activity = $this->activiteRepository->find($id);
        if (!$activity) {
            return ['status' => 'not found'];
        } else {
            return $activity;
        }
    }

    


}
   