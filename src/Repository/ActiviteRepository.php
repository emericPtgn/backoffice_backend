<?php

namespace App\Repository;
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

    // Traitement du nom
    if (isset($requestDatas['nom'])) {
        $existingActivity = $this->findOneBy(['nom' => $requestDatas['nom']]);
        if ($existingActivity) {
            return ['message' => 'Une activité existe avec un nom identique'];
        } else {
            $activity->setNom($requestDatas['nom']);
        }
    }

    // Traitement de la date
    if (isset($requestDatas['date'])) {
        $dateTime = new DateTime($requestDatas['date']);
        // $utc_timezone = new DateTimeZone("UTC");
        // $paris_timezone = new DateTimeZone("Europe/Paris");
        // $dateTime = new DateTime($requestDatas['date'], $paris_timezone);
        // $dateTime->setTimezone($utc_timezone);

        // if ($dateTime === false) {
        //     return ['message' => 'Invalid date format. Please use yyyy-MM-ddThh:mm'];
        // }
        $activity->setDate($dateTime);
    }

    // Traitement du type
    if (isset($requestDatas['type'])) {
        $activity->setType($requestDatas['type']);
    }

    // Traitement de l'emplacement
    if (isset($requestDatas['emplacement'])) {
        $emplacementData = $requestDatas['emplacement'];

        if (isset($emplacementData['id'])) {
            // Rechercher l'emplacement en base de données
            $existingEmplacement = $this->dm->getRepository(Emplacement::class)->findOneBy(['id' => $emplacementData['id']]);
            if ($existingEmplacement) {
                $activity->setEmplacement($existingEmplacement);
            } else {
                // Si l'emplacement n'existe pas, retourner une erreur
                return ['message' => 'Emplacement not found with the provided ID'];
            }
        } else {
            // Créer un nouvel emplacement si aucun ID n'est fourni
            if (!isset($emplacementData['nom']) || !isset($emplacementData['latitude']) || !isset($emplacementData['longitude'])) {
                return ['message' => 'Incomplete emplacement data'];
            }

            $emplacement = new Emplacement(
                $emplacementData['nom'],
                $emplacementData['latitude'],
                $emplacementData['longitude']
            );
            $activity->setEmplacement($emplacement);
        }
    }

    // Traitement de l'artiste
    if (isset($requestDatas['artiste'])) {
        $artisteData = $requestDatas['artiste'];
        $existingArtiste = $this->dm->getRepository(Artiste::class)->findOneBy(['id' => $artisteData['id']]);
        
        if ($existingArtiste) {
            $activity->setArtiste($existingArtiste);
        } else {
            $newArtiste = new Artiste();
            if (isset($artisteData['nom'])) {
                $newArtiste->setNom($artisteData['nom']);
            }
            if (isset($artisteData['description'])) {
                $newArtiste->setDescription($artisteData['description']);
            }
            if (isset($artisteData['style'])) {
                $newArtiste->setStyle($artisteData['style']);
            }
            $this->dm->persist($newArtiste);
            $activity->setArtiste($newArtiste);
        }
    }

    if (isset($requestDatas['description'])) {
        $descriptionData = $requestDatas['description'];
        $activity->setDescription($descriptionData);
    }

    $this->dm->persist($activity);
    $this->dm->flush();
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
                    $activity->setArtiste($isExistingArtiste);
                } else {            
                    $this->log('warning', 'Artiste not found', ['artisteId' => $artisteId]);
                    $newArtiste = new Artiste();
                    $newArtiste->setNom($requestDatas['artiste']['nom'] ?? $requestDatas['artiste']);
                    $this->dm->persist($newArtiste);
                    $activity->setArtiste($newArtiste);
                    
                }
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