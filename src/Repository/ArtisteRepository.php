<?php

namespace App\Repository;
use App\Document\Artiste;
use App\Document\ReseauSocial;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class ArtisteRepository extends DocumentRepository {
    public function __construct(DocumentManager $dm)
    {
        $uow = $dm->getUnitOfWork();
        $classMetaData = $dm->getClassMetadata(Artiste::class);
        parent::__construct($dm, $uow, $classMetaData);
    }
    
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
                    $social->setPseudo($socialData['pseudo']);
                    $social->setIcone($socialData['icone']);
                    $this->dm->persist($social);
                }
                
                $artiste->addReseauSocial($social);
            }
        }
        $this->dm->persist($artiste);
        $this->dm->flush();
        return $artiste;
    }
}