<?php

namespace App\Service;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Document\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService {
    private DocumentManager $dm;
    private UserPasswordHasherInterface $passwordHasher;
    public function __construct(DocumentManager $dm, UserPasswordHasherInterface $passwordHasher)
    {
        $this->dm = $dm;
        $this->passwordHasher = $passwordHasher;
    }
    public function addUser(array $requestDatas) : User
    {
        $user = new User();
        if(isset($requestDatas['email'])){
            $user->setEmail($requestDatas['email']);
        }
        if(isset($requestDatas['password'])){
            $hashPassword = $this->passwordHasher->hashPassword($user,$requestDatas['password']);
            $user->setPassword($hashPassword);
        }
        if(isset($requestDatas['role'])){
            $user->setRoles($requestDatas['role']);
        }
        $this->dm->persist($user);
        $this->dm->flush();
        return $user;
    }

    public function removeUser(string $email)
    {
        $user = $this->dm->getRepository(User::class)->findOneBy(['email' => $email]);
        if(!$user){
            return ['error' => "this user doesn't exist or has been removed"];
        }
        try {
            $this->dm->remove($user);
            $this->dm->flush();
            return ['message' => 'User removed successfully'];
        } catch (\Throwable $th) {
            return ['error' => 'An error has occured, user not been removed'];
        } 
    } 

    public function updateUser(array $requestDatas)
    {
        if(isset($requestDatas['email'])){
            $email = $requestDatas['email'];
            $user = $this->dm->getRepository(User::class)->findOneBy(['email' => $email]);
        }
        if(isset($requestDatas['nom'])){
            $user->setNom($requestDatas['nom']);
        }
        if(isset($requestDatas['groupes'])){
            $user->setGroupes($requestDatas['groupes']);
        }
        if(isset($requestDatas['roles'])){
            $user->setRoles($requestDatas['roles']);
        }
        return '';
    }

    public function getUsers()
    {
        $users = $this->dm->getRepository(User::class)->findAll();
        if(!$users){
            return ['message' => 'no users found, add your first user!'];
        }
        return $users;
    }
}