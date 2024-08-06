<?php

namespace App\Service;
use App\Document\Artiste;
use App\Document\Activite;
use App\Document\Emplacement;
use App\Document\ReseauSocial;
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
        if(isset($requestDatas['reseauxSociaux']) && is_array($requestDatas['reseauxSociaux'])){
            foreach($requestDatas['reseauxSociaux'] as $socialData){
                $social = $this->dm->getRepository(ReseauSocial::class)->findOneBy(['url' => $socialData['url']]);
                
                if(!$social){
                    $social = new ReseauSocial();
                    $social->setPlateforme($socialData['plateforme']);
                    $social->setUrl($socialData['url']);
                    $this->dm->persist($social);
                }
                
                $artiste->addReseauSocial($social);
            }
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
            $this->dm->flush();
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
            if(isset($requestDatas['reseauxSociaux']) && is_array($requestDatas['reseauxSociaux'])){
                $reseauxSociauxCollection = $artiste->getReseauxSociaux();
                foreach ($reseauxSociauxCollection as $reseauSocial) {
                    $artiste->removeReseauSocial($reseauSocial);
                }

                foreach($requestDatas['reseauxSociaux'] as $socialData){
                
                    $existSocial = new ReseauSocial();
                    $existSocial->setPlateforme($socialData['plateforme']);
                    $existSocial->setUrl($socialData['url']);
                    $this->dm->persist($existSocial);
                    $artiste->addReseauSocial($existSocial);
            }
            }
            $this->dm->persist($artiste);
            $this->dm->flush();
            return $artiste;
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
   