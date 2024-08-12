<?php

namespace App\Repository;
use App\Document\Marker;
use DateTime;
use App\Document\Artiste;
use App\Document\Activite;
use DateTimeZone;
use Psr\Log\LoggerInterface;
use App\Document\Emplacement;
use App\Document\TypeActivite;
use Src\Utils\ActivityResponse;
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

public function addActivity(array $requestDatas)
{
    $activity = new Activite();

    $this->log('info', 'ADD NEW ACITIVITY', ['DATA' => $requestDatas]);


    if (isset($requestDatas['nomActivite'])) {
        $existingActivity = $this->dm->getRepository(Activite::class)->findOneBy(['nom' => $requestDatas['nomActivite']]);
        $this->log('info', 'EXISTING ACITIVYT ?', ['DATA' => $existingActivity]);
        if ($existingActivity) {
            return ['message' => 'Une activité existe avec un nom identique'];
        } else {
            $activity->setNom($requestDatas['nomActivite']);
        }
    }

    if (isset($requestDatas['dateTime'])) {
        try {
            $dateTime = new \DateTime($requestDatas['dateTime']);
            $activity->setDate($dateTime);
        } catch (\Exception $e) {
            return ['message' => 'Invalid date format. Please use yyyy-MM-ddThh:mm'];
        }
    }

    if (isset($requestDatas['typeActivity'])) {
        $activity->setType($requestDatas['typeActivity']);
    }

    if (isset($requestDatas['marker'])) {
        $markerData = $requestDatas['marker'];

        if (isset($markerData['id'])) {
            $existingMarker = $this->dm->getRepository(Marker::class)->findOneBy(['id' => $markerData['id']]);
            if ($existingMarker) {
                $activity->setMarker($existingMarker);
            } else {
                return ['message' => 'Marker not found with the provided ID'];
            }
        } else {
            if (!isset($markerData['nom']) || !isset($markerData['latitude']) || !isset($markerData['longitude'])) {
                return ['message' => 'Incomplete markerData'];
            }

            $marker = new Marker();
            $marker->setNom($markerData['nom']);
            $marker->setType($markerData['type'] ?? 'default'); // Ajout d'un type par défaut si non fourni
            $marker->setLatitude($markerData['latitude']);
            $marker->setLongitude($markerData['longitude']);
            
            $this->dm->persist($marker);
            $activity->setMarker($marker);
        }
    }

    if (isset($requestDatas['artiste'])) {
        $artisteData = $requestDatas['artiste'];
        $this->log('info', 'ISSET ARTISTE ACTIVITY ?', ['DATA' => $artisteData]);

        $existingArtiste = $this->dm->getRepository(Artiste::class)->findOneBy(['nom' => $artisteData['nom']]);
        if ($existingArtiste) {
            $this->log('info', 'IS EXISTING ARTISTE ?', ['DATA' => $existingArtiste]);
            $activity->addArtiste($existingArtiste);
            $this->log('info', 'ACTIVITY UPDATED WITH ARTISTE ?', ['ACTIVITY' => $activity]);
            $existingArtiste->addActivite($activity); // Ajoutez cette ligne
        } else {
            $newArtiste = new Artiste();
            if (isset($artisteData['nom'])) {
                $newArtiste->setNom($artisteData['nom']);
            }
            if (isset($artisteData['description'])) {
                $newArtiste->setDescription($artisteData['description']);
            }
            if (isset($artisteData['style']) && is_array($artisteData['style'])) {
                $newArtiste->setStyles($artisteData['style']);
            }
            $this->log('info', 'NEW ARTIST ABOUT TO PERSIST ?', ['ARTIST' => $newArtiste]);
            $this->dm->persist($newArtiste);
            $activity->addArtiste($newArtiste);
            $this->log('info', 'ACTIVITY UPDATED WITH ARTISTE ?', ['ACTIVITY' => $activity]);
            $newArtiste->addActivite($activity); // Ajoutez cette ligne
            $this->log('info', 'ARTISTE UPDATED WITH ACTIVITY', ['ARTISTE' => $newArtiste]);
        }
    }
    
    if (isset($requestDatas['description'])) {
        $activity->setDescription($requestDatas['description']);
    }
    $this->log('info', 'ACTIVITY ABOUT TO BE PERSIST', ['ACTIVITY' => $activity]);
    $this->dm->persist($activity);
    $this->log('info', 'ACTIVITY PERSISTED', ['ACTIVITY' => $activity]);
    $this->dm->flush();
    
    $this->log('info', 'Activity saved', ['activityId' => $activity->getId(), 'artistes' => $activity->getArtistes()->toArray()]);
    
    return $activity;
}

public function getActivity(string $id): ActivityResponse
{
    $activity = $this->find($id);
    if (!$activity) {
        return new ActivityResponse(null, 'no activity found');
    } else {
        return new ActivityResponse($activity);
    }
}
    
public function updateActivity(string $id, array $requestDatas)
{
    try {
        $this->log('info', 'Starting activity update', ['id' => $id, 'requestData' => $requestDatas]);

        $activity = $this->dm->getRepository(Activite::class)->find($id);
        if (!$activity) {
            $this->log('warning', 'Activity not found', ['id' => $id]);
            return ['message' => 'activity not found'];
        }

        if (isset($requestDatas['nom'])) {
            $activity->setNom($requestDatas['nom']);
        }

        if (isset($requestDatas['date'])) {
            $date = new DateTime($requestDatas['date']);
            $this->log('info', 'date format', ['date' => $requestDatas['date']]);
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
            $this->log('info', 'Updating artiste', ['artisteId' => $artisteId, 'isExisting' => (bool)$isExistingArtiste]);
        
            if($isExistingArtiste){
                $activity->addArtiste($isExistingArtiste);
            } else {            
                $this->log('warning', 'Artiste not found', ['artisteId' => $artisteId]);
                $newArtiste = new Artiste();
                $newArtiste->setNom($requestDatas['artiste']['nom'] ?? $requestDatas['artiste']);
                $this->dm->persist($newArtiste);
                $activity->addArtiste($newArtiste);
                
            }
        }

        if(isset($activityData['location'])){
            $this->log('info', 'LOCATION ACTIVITY', ['LOCATION' => $requestDatas['location']]);
            $location = $this->dm->getRepository(Marker::class)->findOneBy(['nom' => $requestDatas['location']]);
            $activity->setMarker($location);
        }

        $this->dm->persist($activity);
        $this->log('info', 'Activity persisted, about to flush');
        
        try {
            $this->dm->flush();
            $this->log('info', 'Flush successful');
        } catch (\Exception $e) {
            $this->log('error', 'Flush failed', ['error' => $e->getMessage()]);
            throw $e;
        }

        // Refresh the activity from the database to ensure we have the latest data
        //$this->dm->refresh($activity);
        $this->log('info', 'Activity updated successfully', ['id' => $activity->getId()]);
        return $activity;
    } catch (\Exception $e) {
        $this->log('error', 'Error updating activity', ['id' => $id, 'error' => $e->getMessage()]);
        return ['message' => 'Une erreur est survenue lors de la mise à jour de l\'activité: ' . $e->getMessage()];
    }
}

public function deleteActivity(string $id){
    $activity = $this->dm->getRepository(Activite::class)->find($id);
    if(!$activity){
        return ['mesage' => 'no activity found'];
    } else {
        $this->dm->remove($activity);
        $this->dm->flush();
        return ['message' => 'activity removed successfully'];
    }
}


    
    
}