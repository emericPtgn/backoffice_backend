<?php

namespace App\Service;
use App\Document\Marker;
use App\Document\Artiste;
use App\Document\Activite;
use App\Document\Emplacement;
use App\Document\ReseauSocial;
use App\Repository\ArtisteRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Psr\Log\LoggerInterface;
use App\Utils\DocumentPersister;

class ArtisteService {
    private DocumentManager $dm;
    private ArtisteRepository $artisteRepo;
    private LoggerInterface $logger;
    
    public function __construct(DocumentManager $dm, ArtisteRepository $artisteRepo, LoggerInterface $logger)
    {
        $this->dm = $dm;
        $this->artisteRepo = $artisteRepo;
        $this->logger = $logger;
    }

    // instancie nouvel artiste et persiste en base 
    public function addArtiste(array $requestDatas) 
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
    
            foreach ($requestDatas['activities'] as $activityData) {
                $existingActivity = $this->dm->getRepository(Activite::class)->findOneBy(['nom' => $activityData['nom']]);
    
                if (!$existingActivity) {
                    $newActivity = new Activite();
                    if(isset($activityData['nom'])){
                        $newActivity->setNom($activityData['nom']);
                    }
                    if(isset($activityData['date'])){
                        $newDateTime = new \DateTime($activityData['date']);
                        $newActivity->setDate($newDateTime);
                    }
                    if(isset($activityData['type'])){
                        $newActivity->setType($activityData['type']);
                    }
                    if(isset($activityData['location'])){
                        $location = $this->dm->getRepository(Marker::class)->findOneBy(['nom' => $activityData['location']]);
                        $newActivity->setMarker($location);
                    }
                    $this->dm->persist($newActivity);
                    $this->dm->flush();
                    $artiste->addActivite($newActivity);
                    $newActivity->addArtiste($artiste);
                } else {
                    $artiste->addActivite($existingActivity);
                    $existingActivity->addArtiste($artiste);
                }
            }
        }
    
        return DocumentPersister::persistDocument($this->dm, $artiste);

    }
    // correspondance par id identifie l'artiste à effacer de la base
    public function removeArtiste(string $id)
    {
        // Trouver l'artiste à supprimer
        $artiste = $this->dm->getRepository(Artiste::class)->find($id);
        
        if (!$artiste) {
            return [
                'message' => 'Artist not found',
                'status' => 'error'
            ];
        }
    
        try {
            // Trouver toutes les activités associées à l'artiste
            $activities = $this->dm->getRepository(Activite::class)->findBy(['artistes' => $artiste]);
            
            foreach ($activities as $activity) {
                $activity->removeArtiste($artiste);
                $this->dm->persist($activity);
            }
    
            // Supprimer l'artiste
            $this->dm->remove($artiste);
            $this->dm->flush();
    
            return [
                'message' => 'Artist removed successfully',
                'status' => 'success'
            ];
        } catch (\Exception $exception) {
            // Log l'erreur pour le débogage
            $this->logger->error('An error occurred while removing artist: ' . $exception->getMessage());
    
            return [
                'message' => 'An error occurred: ' . $exception->getMessage(),
                'status' => 'error'
            ];
        }
    }
    
    // correspondance par id identifie l'ratiste à mettre à jour en base
    // données issues de l'état local du composant REACT mis à jour 
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
        
                foreach ($requestDatas['activities'] as $activityData) {
                    $existingActivity = $this->dm->getRepository(Activite::class)->findOneBy(['nom' => $activityData['nom']]);
        
                    if (!$existingActivity) {
                        $newActivity = new Activite();
                        if(isset($activityData['nom'])){
                            $newActivity->setNom($activityData['nom']);
                        }
                        if(isset($activityData['date'])){
                            $newDateTime = new \DateTime($activityData['date']);
                            $newActivity->setDate($newDateTime);
                        }
                        if(isset($activityData['type'])){
                            $newActivity->setType($activityData['type']);
                        }
                        if(isset($activityData['location'])){
                            $location = $this->dm->getRepository(Marker::class)->findOneBy(['nom' => $activityData['location']]);
                            $newActivity->setMarker($location);
                        }
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
                            $location = $this->dm->getRepository(Marker::class)->findOneBy(['nom' => $activityData['location']]);
                            $existingActivity->setMarker($location);
                        }
                        if(isset($activityData['type'])){
                            $existingActivity->setType($activityData['type']);
                        }
                        $artiste->addActivite($existingActivity);
                        $existingActivity->addArtiste($artiste);
                    }
                }
            }

            return DocumentPersister::persistDocument($this->dm, $artiste);
        }
    }
    // extrait la liste de TOUS les artistes enregistrées en base
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
   