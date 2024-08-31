<?php

namespace App\Repository;
use DateTime;
use App\Document\Activite;
use Psr\Log\LoggerInterface;
use App\Document\Programmation;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class ProgrammationRepository extends DocumentRepository {

    private ?LoggerInterface $logger = null;
    public function __construct(DocumentManager $dm)
    {
        $uow = $dm->getUnitOfWork();
        $classMetaData = $dm->getClassMetadata(Programmation::class);
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
public function addProgrammation(array $requestDatas)
{
    $this->log('info', 'addProgrammation method called', ['requestData' => $requestDatas]);
    
    $programmation = new Programmation();

    if (isset($requestDatas['titre'])) {
        $this->log('info', 'isset title', ['title' => $requestDatas['titre']]);
        $isExistingTitle = $this->findOneBy(['titre' => $requestDatas['titre']]);
        $this->log('info', 'is existing title ?', ['title' => $isExistingTitle]);

        if ($isExistingTitle) {
            return ['message' => "This title is already used, find a new title for this programmation"];
        }

        $programmation->setTitre($requestDatas['titre']);
    }

    if (isset($requestDatas['description'])) {
        $programmation->setDescription($requestDatas['description']);
        $this->log('info', 'isset description', ['description' => $requestDatas['description']]);
    }

    if (isset($requestDatas['dateDebut'])) {
        $dateDebut = new \DateTime($requestDatas['dateDebut']);
        $programmation->setDateDebut($dateDebut);
        $this->log('info', 'isset dateDebut', ['dateDebut raw' => $requestDatas['dateDebut'], 'dateDebut formatted' => $dateDebut]);
    }

    if (isset($requestDatas['dateFin'])) {
        $dateFin = new \DateTime($requestDatas['dateFin']);
        $programmation->setDateFin($dateFin);
        $this->log('info', 'isset dateFin', ['dateFin raw' => $requestDatas['dateFin'], 'dateFin formatted' => $dateFin]);
    }

    if (isset($requestDatas['activites'])) {
        $this->log('info', 'ISSET ACTIVITES', ['ACTIVITES' => $requestDatas['activites']]);
    
        $activiteRepository = $this->dm->getRepository(Activite::class);
        $activitesData = is_array($requestDatas['activites']) ? $requestDatas['activites'] : [$requestDatas['activites']];
    
        foreach ($activitesData as $activiteData) {
            $activiteData = is_array($activiteData) ? $activiteData : [$activiteData];
            if (is_array($activiteData)) {
                $this->log('info', 'ACTIVITEDATA', ['ACTIVITEDATA' => $activiteData]);
    
                $existingActivity = null;
                if (isset($activiteData['id'])) {
                    $existingActivity = $activiteRepository->findOneBy(['id' => $activiteData['id']]);
                }
    
                if ($existingActivity) {
                    $activiteRepository->updateActivity($existingActivity->getId(), $activiteData);
                    $programmation->addActivite($existingActivity);
                } else {
                    $newActivity = new Activite();
                    if (isset($activiteData['nom'])) {
                        $newActivity->setNom($activiteData['nom']);
                    }
                    if (isset($activiteData['date'])) {
                        $newActivity->setDate(new DateTime($activiteData['date']));
                    }
                    // Ajouter les autres propriétés nécessaires ici
                    $this->dm->persist($newActivity);
                    $this->dm->flush();
                    $programmation->addActivite($newActivity);
                }
            } else {
                throw new \Exception('Invalid activite data format. Each activite must be an array.');
            }
        }
    }

    $this->dm->persist($programmation);
    $this->dm->flush();

    $this->log('info', 'programmation saved', ['programmation' => $programmation]);

    return $programmation;
}

    public function getAllProgrammations(){
        if($programmation = $this->findAll()){
            return $programmation;
        } else {
            return ['message' => 'no programmation found, create a new one !']; 
        }
    }

    public function deleteProgrammation(string $id){
        if($programmation = $this->find($id)){
            $this->dm->remove($programmation);
            return ['message' => "programmation has been deleted (ID:.$id.)"];
        } else {
            return ['message' => 'oups something went wrong, programmation cannot be deleted'];
        }
    }

    public function getProgrammation(string $id){
        if($programmation = $this->find($id)){
            return $programmation;
        } else {
            return ['message' => "oups something went wrong, cant find this programmation (ID:.$id.)"];
        }

    }

    public function updateProgrammation(string $id, array $requestDatas)
    {
        if ($programmation = $this->find($id)) {
            if (isset($requestDatas['titre'])) {
                $this->log('info', 'ISSET TITLE', ['TITLE' => $requestDatas['titre']]);
                // Rechercher une programmation qui possède le même titre
                $isExistingTitle = $this->findOneBy(['titre' => $requestDatas['titre']]);
                // Si une programmation QUI N'A PAS LE MEME ID possède le même titre, alors bloquer l'enregistrement
                if ($isExistingTitle && $isExistingTitle->getId() !== $id) {
                    // Retourner un message et sortir de la fonction
                    return ['message' => "This title is already used, find a new title for this programmation"];
                }
                $programmation->setTitre($requestDatas['titre']);
            }
    
            if (isset($requestDatas['description'])) {
                $this->log('info', 'ISSET DESCRIPTION', ['DESCRIPTION' => $requestDatas['description']]);
                $programmation->setDescription($requestDatas['description']);
            }
    
            if (isset($requestDatas['dateDebut'])) {
                $this->log('info', 'ISSET DATEDEBUT', ['DATEDEBUT' => $requestDatas['dateDebut']]);

                $dateDebut = new \DateTime($requestDatas['dateDebut']);
                $programmation->setDateDebut($dateDebut);
            }
        
            if (isset($requestDatas['dateFin'])) {
                $this->log('info', 'ISSET DATEFIN', ['DATEFIN' => $requestDatas['dateFin']]);

                $dateFin = new \DateTime($requestDatas['dateFin']);
                $programmation->setDateFin($dateFin);
            }

            if (isset($requestDatas['activites'])) {
                $this->log('info', 'ISSET ACTIVITES', ['ACTIVITES' => $requestDatas['activites']]);
            
                // Supprimer toutes les activités existantes de la programmation
                foreach ($programmation->getActivites() as $activite) {
                    $programmation->removeActivite($activite);
                }
            
                $activiteRepository = $this->dm->getRepository(Activite::class);
                $activitesData = is_array($requestDatas['activites']) ? $requestDatas['activites'] : [$requestDatas['activites']];
            
                foreach ($activitesData as $activiteData) {
                    $activiteData = is_array($activiteData) ? $activiteData : [$activiteData];
                    if (is_array($activiteData)) {
                        $this->log('info', 'ACTIVITEDATA', ['ACTIVITEDATA' => $activiteData]);
            
                        $existingActivity = null;
                        if (isset($activiteData['id'])) {
                            $existingActivity = $activiteRepository->findOneBy(['id' => $activiteData['id']]);
                        }
            
                        if ($existingActivity) {
                            $activiteRepository->updateActivity($existingActivity->getId(), $activiteData);
                            $programmation->addActivite($existingActivity);
                        } else {
                            $newActivity = new Activite();
                            if (isset($activiteData['nom'])) {
                                $newActivity->setNom($activiteData['nom']);
                            }
                            if (isset($activiteData['date'])) {
                                $newActivity->setDate(new DateTime($activiteData['date']));
                            }
                            // Ajouter les autres propriétés nécessaires ici
                            $this->dm->persist($newActivity);
                            $this->dm->flush();
                            $programmation->addActivite($newActivity);
                        }
                    } else {
                        throw new \Exception('Invalid activite data format. Each activite must be an array.');
                    }
                }
            }
            

            // Persist the changes (assuming this is needed)
            $this->dm->persist($programmation);
            $this->dm->flush();
            return ['message' => 'Programmation updated successfully',
        'data' => $programmation];
        } else {
            return ['message' => "Programmation not found (ID: $id)"];
        }
    }
    
    public function formatDateCustomFormat(string $dateString): ?DateTime
    {
        // Remplacer les deux-points par un "T" pour rendre le format compatible avec DateTime
        $formattedDateString = str_replace(':', 'T', $dateString);
    
        try {
            $date = new DateTime($formattedDateString);
            return $date;
        } catch (\Exception $e) {
            return null; // Si la date est invalide, retourner null
        }
    }
    

    
}