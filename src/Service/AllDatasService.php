<?php

namespace App\Service;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Document\Activite;
use App\Document\Artiste;
use App\Document\Commerce;
use App\Document\Emplacement;
use App\Document\Marker;
use App\Document\ReseauSocial;
use App\Document\Scene;
use App\Document\TypeActivite;
use App\Document\TypeCommerce;
use App\Document\TypeProduit;
use App\Document\User;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Component\Security\Core\User\UserInterface;


class AllDatasService {
    private DocumentManager $dm;
    private Security $security;
    public function __construct(DocumentManager $dm, Security $security){
        $this->security = $security;
        $this->dm = $dm;
    }

    public function getAllDatas(UserInterface $user)
    {
        $allDatas = [];
        // Données accessibles à tous les utilisateurs authentifiés
        $allDatas['activites'] = $this->dm->getRepository(Activite::class)->findAll();
        $allDatas['artistes'] = $this->dm->getRepository(Artiste::class)->findAll();
        $allDatas['commerces'] = $this->dm->getRepository(Commerce::class)->findAll();
        $allDatas['reseauxSociaux'] = $this->dm->getRepository(ReseauSocial::class)->findAll();
        $allDatas['scenes'] = $this->dm->getRepository(Scene::class)->findAll();
        $allDatas['typesActivites'] = $this->dm->getRepository(TypeActivite::class)->findAll();
        $allDatas['typesCommerces'] = $this->dm->getRepository(TypeCommerce::class)->findAll();
        $allDatas['typesProduits'] = $this->dm->getRepository(TypeProduit::class)->findAll();
        // Données accessibles aux managers et admins
        if ($this->security->isGranted('ROLE_EDITEUR')) {
        $allDatas['emplacements'] = $this->dm->getRepository(Emplacement::class)->findAll();
        $allDatas['markers'] = $this->dm->getRepository(Marker::class)->findAll();
        }
        // Données accessibles uniquement aux admins
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $allDatas['users'] = $this->dm->getRepository(User::class)->findAll();
        }

        return $allDatas;
    }


}