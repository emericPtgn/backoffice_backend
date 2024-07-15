<?php

namespace App\Service;
use App\Document\Artiste;
use Doctrine\ODM\MongoDB\DocumentManager;

class ArtisteService {
    private DocumentManager $dm;
    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    // instancie nouvel artiste et persiste en base 
    public function addArtiste(array $requestDatas)
    {
        $artiste = new Artiste();
        if(isset($requestDatas['nom'])){
            $artiste->setNom($requestDatas['nom']);
        }
        if(isset($requestDatas['description'])){
            $artiste->setDescription($requestDatas['description']);
        }
        if(isset($requestDatas['style'])){
            $artiste->setStyle($requestDatas['style']);
        }
        if(isset($requestDatas['style'])){
            $artiste->addReseauSocial($requestDatas['style']);
        }
        $this->dm->persist($artiste);
        $this->dm->flush();
        return $artiste;
    }


    // correspondance par id identifie l'artiste à effacer de la base
    public function removeArtiste(string $id){
        $artiste = $this->dm->getRepository(Artiste::class)->find($id);
        if(!$artiste){
            return ['mesage' => 'no artiste found'];
        } else {
            $this->dm->remove($artiste);
            return ['message' => 'artiste removed successfully'];
        }
    }

    // correspondance par id identifie l'ratiste à mettre à jour en base
    // données issues de l'état local du composant REACT mis à jour 
    public function updateArtiste(string $id, array $requestDatas){
        $artiste = $this->dm->getRepository(Artiste::class)->find($id);
        if(!$artiste){
            return ['message' => 'artiste not found'];
        } else {
            if(isset($requestDatas['nom'])){
                $artiste->setNom($requestDatas['nom']);
            }
            if(isset($requestDatas['description'])){
                $artiste->setDescription($requestDatas['description']);
            }
            if(isset($requestDatas['style'])){
                $artiste->setStyle($requestDatas['style']);
            }
            if(isset($requestDatas['style'])){
                $artiste->addReseauSocial($requestDatas['style']);
            }
            $this->dm->persist($artiste);
            $this->dm->flush();
        }
    }

    // extrait la liste de TOUS les artistes enregistrées en base
    public function getArtistes(){
        $artistes = $this->dm->getRepository(Artiste::class)->findAll();
        if(!$artistes){
            return ['message' => 'no artiste found'];
        } else {
            return $artistes;
        }
    }

    // correspondance par ID vérifie présence de l'artiste en base, si non retourne msg erreur, si oui retourne artiste
    public function getArtiste(string $id){
        $artiste = $this->dm->getRepository(Artiste::class)->find($id);
        if(!$artiste){
            return ['message' => 'no artiste found'];
        } else {
            return $artiste;
        }
    }

}
   