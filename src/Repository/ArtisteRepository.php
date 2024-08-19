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

    public function addArtiste(array $requestDatas) : Artiste
    {
        $artiste = new Artiste();
    
        if (isset($requestDatas['nom'])) {
            $artiste->setNom($requestDatas['nom']);
        }
    
        if (isset($requestDatas['description'])) {
            $artiste->setDescription($requestDatas['description']);
        }
    
        if (isset($requestDatas['styles']) && is_array($requestDatas['styles'])) {
            $artiste->setStyles($requestDatas['styles']);
        }
    
        if (isset($requestDatas['reseauxSociaux']) && is_array($requestDatas['reseauxSociaux'])) {
            foreach ($requestDatas['reseauxSociaux'] as $socialData) {
                $social = $this->dm->getRepository(ReseauSocial::class)->findOneBy(['url' => $socialData['url']]);
    
                if (!$social) {
                    $social = new ReseauSocial();
                    $social->setPlateforme($socialData['plateforme']);
                    $social->setUrl($socialData['url']);
                    $this->dm->persist($social);
                }
    
                $artiste->addReseauSocial($social);
            }
        }
    
        if (isset($requestDatas['activities']) && is_array($requestDatas['activities'])) {
            $this->log('info', 'ACTIVITES', ['ACTIVITE' => $requestDatas['activities']]);
    
            foreach ($requestDatas['activities'] as $activityData) {
                $this->log('info', 'FOR EACH ACTIVITY DATA', ['LIKE' => $activityData]);
                $existingActivity = $this->dm->getRepository(Activite::class)->findOneBy(['nom' => $activityData['nom']]);
                $this->log('info', 'EXISTING ACTIVITY', ['IS' => $existingActivity]);
    
                if (!$existingActivity) {
                    $this->log('info', 'EXISTING ACTIVITY NOT FOUND', ['VALUE' => $existingActivity]);
                    $newActivity = new Activite();
                    if(isset($activityData['nom'])){
                        $newActivity->setNom($activityData['nom']);
                        $this->log('info', 'NOM ACTIVITY', ['NOM' => $newActivity->getNom()]);
                    }
                    if(isset($activityData['date'])){
                        $newDateTime = new \DateTime($activityData['date']);
                        $newActivity->setDate($newDateTime);
                    }
                    if(isset($activityData['type'])){
                        $newActivity->setType($activityData['type']);
                        $this->log('info', 'TYPE ACTIVITE', ['TYPE' => $newActivity->getType()]);
                    }
                    if(isset($activityData['location'])){
                        $this->log('info', 'LOCATION ACTIVITY', ['LOCATION' => $activityData['location']]);
                        $location = $this->dm->getRepository(Marker::class)->findOneBy(['nom' => $activityData['location']]);
                        $newActivity->setMarker($location);
                    }
                    $this->log('info', 'NEW ACTIVITY CREATED', ['DATA' => $newActivity]);
                    $this->dm->persist($newActivity);
                    $this->dm->flush();
                    $artiste->addActivite($newActivity);
                    $newActivity->addArtiste($artiste);
                } else {
                    $artiste->addActivite($existingActivity);
                    $existingActivity->addArtiste($artiste);
                    $this->log('info', 'EXISTING ACTIVITY ADDED', ['VALUE' => $existingActivity]);
                }
            }
        }
    
        $this->dm->persist($artiste);
        $this->dm->flush();
        
        return $artiste;
    }

    public function updateArtiste(string $id, array $requestDatas)
    {
        $artiste = $this->dm->getRepository(Artiste::class)->find($id);
        if (!$artiste) {
            return ['message' => 'artiste not found'];
        } else {
            if (isset($requestDatas['nom'])) {
                $artiste->setNom($requestDatas['nom']);
            };
            if (isset($requestDatas['description'])) {
                $artiste->setDescription($requestDatas['description']);
            };
            if (isset($requestDatas['styles'])) {
                $artiste->setStyles($requestDatas['styles']);
            };
            if (isset($requestDatas['reseauxSociaux']) && is_array($requestDatas['reseauxSociaux'])) {
                $reseauxSociauxCollection = $artiste->getReseauxSociaux();
                foreach ($reseauxSociauxCollection as $reseauSocial) {
                    $artiste->removeReseauSocial($reseauSocial);
                }
                foreach ($requestDatas['reseauxSociaux'] as $socialData) {
                    $existSocial = new ReseauSocial();
                    $existSocial->setPlateforme($socialData['plateforme']);
                    $existSocial->setUrl($socialData['url']);
                    $this->dm->persist($existSocial);
                    $artiste->addReseauSocial($existSocial);
                }
            };
            if (isset($requestDatas['activities']) && is_array($requestDatas['activities'])) {
                $artistActivities = $artiste->getActivities();
                if($artistActivities){
                    foreach ($artistActivities as $activity) {
                        $artiste->removeActivite($activity);
                    }
                }
                $this->log('info', 'ACTIVITES', ['ACTIVITE' => $requestDatas['activities']]);
        
                foreach ($requestDatas['activities'] as $activityData) {
                    $this->log('info', 'FOR EACH ACTIVITY DATA', ['LIKE' => $activityData]);
                    $existingActivity = $this->dm->getRepository(Activite::class)->findOneBy(['nom' => $activityData['nom']]);
                    $this->log('info', 'EXISTING ACTIVITY', ['IS' => $existingActivity]);
        
                    if (!$existingActivity) {
                        $this->log('info', 'EXISTING ACTIVITY NOT FOUND', ['VALUE' => $existingActivity]);
                        $newActivity = new Activite();
                        if(isset($activityData['nom'])){
                            $newActivity->setNom($activityData['nom']);
                            $this->log('info', 'NOM ACTIVITY', ['NOM' => $newActivity->getNom()]);
                        }
                        if(isset($activityData['date'])){
                            $newDateTime = new \DateTime($activityData['date']);
                            $newActivity->setDate($newDateTime);
                        }
                        if(isset($activityData['type'])){
                            $newActivity->setType($activityData['type']);
                            $this->log('info', 'TYPE ACTIVITE', ['TYPE' => $newActivity->getType()]);
                        }
                        if(isset($activityData['location'])){
                            $this->log('info', 'LOCATION ACTIVITY', ['LOCATION' => $activityData['location']]);
                            $location = $this->dm->getRepository(Marker::class)->findOneBy(['nom' => $activityData['location']]);
                            $newActivity->setMarker($location);
                        }
                        $this->log('info', 'NEW ACTIVITY CREATED', ['DATA' => $newActivity]);
                        $this->dm->persist($newActivity);
                        $this->dm->flush();
                        $artiste->addActivite($newActivity);
                        $newActivity->addArtiste($artiste);
                    } else {
                        if(isset($activityData['date'])){
                            $newDateTime = new \DateTime($activityData['date']);
                            $existingActivity->setDate($newDateTime);
                        }
                        if(isset($activityData['location'])){
                            $this->log('info', 'LOCATION ACTIVITY', ['LOCATION' => $activityData['location']]);
                            $location = $this->dm->getRepository(Marker::class)->findOneBy(['nom' => $activityData['location']]);
                            $existingActivity->setMarker($location);
                        }
                        if(isset($activityData['type'])){
                            $existingActivity->setType($activityData['type']);
                        }
                        $artiste->addActivite($existingActivity);
                        $existingActivity->addArtiste($artiste);
                        $this->log('info', 'EXISTING ACTIVITY ADDED', ['VALUE' => $existingActivity]);
                    }
                }
            }


            $this->dm->persist($artiste);
            $this->dm->flush();
            return $artiste;
        }
    }

    public function removeArtiste(string $id)
    {
        $artiste = $this->dm->getRepository(Artiste::class)->find($id);
    
        if (!$artiste) {
            return [
                'message' => 'Artist not found',
                'statut' => 'error'
            ];
        }
    
        try {
            $this->dm->remove($artiste);
            $this->dm->flush();
    
            return [
                'message' => 'Artist removed successfully',
                'statut' => 'success'
            ];
        } catch (\Exception $exception) {
            return [
                'message' => 'An error occurred: ' . $exception->getMessage(),
                'statut' => 'error'
            ];
        }
    }
    

    public function getArtistes()
    {
        $artistes = $this->dm->getRepository(Artiste::class)->findAll();
        if (!$artistes) {
            return [
                'message' => 'no artiste found'
            ];
        } else {
            return $artistes;
        }
    }

    public function getArtiste(string $id)
    {
        $artiste = $this->dm->getRepository(Artiste::class)->find($id);
        if (!$artiste) {
            return ['message' => 'no artiste found'];
        } else {
            return $artiste;
        }
    }
}
