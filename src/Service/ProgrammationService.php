<?php

namespace App\Service;
use App\Document\Activite;
use App\Document\Programmation;
use App\Repository\ProgrammationRepository;
use Doctrine\ODM\MongoDB\DocumentManager;

class ProgrammationService {

    private DocumentManager $dm;
    private ProgrammationRepository $programmationRepository;

    public function __construct(DocumentManager $dm, ProgrammationRepository $programmationRepository){
        $this->programmationRepository = $programmationRepository;
        $this->dm = $dm;
    }

    public function addProgrammation(array $requestDatas){
    $programmation = $this->programmationRepository->addProgrammation($requestDatas);
    return $programmation;
    }

    public function getAllProgrammations(){
        $programmations = $this->programmationRepository->getAllProgrammations();
        return $programmations;
    }

    public function getProgrammation(string $id){
       return $this->programmationRepository->getProgrammation($id);
    }

    public function deleteProgrammation(string $id){
        return $this->programmationRepository->deleteProgrammation($id);
    }
    
    public function updateProgrammation(string $id, array $requestDatas){
        return $this->programmationRepository->updateProgrammation($id, $requestDatas);
    }
}