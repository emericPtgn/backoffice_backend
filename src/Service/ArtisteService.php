<?php

namespace App\Service;
use App\Document\Artiste;
use App\Document\Activite;
use App\Document\Emplacement;
use App\Document\ReseauSocial;
use App\Repository\ArtisteRepository;
use Doctrine\ODM\MongoDB\DocumentManager;

class ArtisteService {
    private DocumentManager $dm;
    private ArtisteRepository $artisteRepo;
    public function __construct(DocumentManager $dm, ArtisteRepository $artisteRepo)
    {
        $this->dm = $dm;
        $this->artisteRepo = $artisteRepo;
    }

    // instancie nouvel artiste et persiste en base 
    public function addArtiste(array $requestDatas) 
    {
        return $this->artisteRepo->addArtiste($requestDatas);
    }

    // correspondance par id identifie l'artiste à effacer de la base
    public function removeArtiste(string $id){
        return $this->artisteRepo->removeArtiste($id);
    }

    // correspondance par id identifie l'ratiste à mettre à jour en base
    // données issues de l'état local du composant REACT mis à jour 
    public function updateArtiste(string $id, array $requestDatas){
        return $this->artisteRepo->updateArtiste($id, $requestDatas);
    }

    // extrait la liste de TOUS les artistes enregistrées en base
    public function getArtistes(){
        return $this->artisteRepo->getArtistes();
    }

    // correspondance par ID vérifie présence de l'artiste en base, si non retourne msg erreur, si oui retourne artiste
    public function getArtiste(string $id){
        return $this->artisteRepo->getArtiste($id);
    }

}
   