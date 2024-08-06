<?php

namespace App\Service;
use App\Repository\UserRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Document\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService {
    private DocumentManager $dm;
    private UserPasswordHasherInterface $passwordHasher;

    private UserRepository $userRepo;
    public function __construct(DocumentManager $dm, UserPasswordHasherInterface $passwordHasher, UserRepository $userRepo)
    {
        $this->dm = $dm;
        $this->passwordHasher = $passwordHasher;
        $this->userRepo = $userRepo;
    }
    public function addUser(array $requestDatas) : User
    {
        return $this->userRepo->addUser($requestDatas);
    }

    public function deleteUser(string $id)
    {
        return $this->userRepo->deleteUser($id);
    } 

    public function updateUser(array $requestDatas, string $id)
    {
        return $this->userRepo->updateUser($requestDatas, $id);
    }

    public function getUsers()
    {
        return $this->userRepo->getUsers();
    }
}