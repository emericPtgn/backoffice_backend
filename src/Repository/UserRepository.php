<?php

namespace App\Repository;

use App\Document\User;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;


class UserRepository extends ServiceDocumentRepository implements PasswordUpgraderInterface 
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(ManagerRegistry $registry, UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getDocumentManager()->persist($user);
        $this->getDocumentManager()->flush();
    }

    public function addUser(array $requestDatas)
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

    public function deleteUser(string $id)
    {
        $user = $this->find($id);
        if(!$user){
            return ['error' => "this user doesn't exist or has been removed"];
        }
        try {
            $this->dm->remove($user);
            $this->dm->flush();
            return [
                'message' => 'User removed successfully',
                'status' => 'success'
            ];
        } catch (\Throwable $th) {
            return ['error' => 'An error has occured, user not been removed'];
        } 
    } 

    public function updateUser(array $requestDatas, string $id)
    {
        $user = $this->find($id);
        if(!$user){
            return ['message' => 'this user does not exist'];
        } else {
            if(isset($requestDatas['email'])){
                $user->setEmail($requestDatas['email']);
            }
            if(isset($requestDatas['nom'])){
                $user->setNom($requestDatas['nom']);
            }
            if (isset($requestDatas['roles'])) {
                $rolesDatas = is_array($requestDatas['roles']) ? $requestDatas['roles'] : [$requestDatas['roles']];
                $user->setRoles($rolesDatas);
            }
            if(isset($requestDatas['groupes'])){
                $groupesDatas = is_array($requestDatas['groupes']) ? $requestDatas['groupes'] : [$requestDatas['groupes']];
                $user->setGroupes($groupesDatas);
            }
            if(isset($requestDatas['password'])){
                $plainPassword = $requestDatas['password'];
                $haspassword = $this->passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($haspassword);
            }
        }

        return $user;;
    }

    public function getUsers()
    {
        $users = $this->findAll();
        if(count($users) === 0){
            return ['message' => 'no users found, add your first user!'];
        }
        return $users;
    }

    public function getThisUser(string $id){
        $user = $this->find($id);
        if(!$user){
            return [
                'message' => 'no users found',
                'status' => 'not found'
            ];
        }
        return $user;
    }

    public function resetPassword(array $requestDatas, string $token, PasswordAuthenticatedUserInterface $user){
        $findUser = $this->findOneBy(['verificationToken' => $token]);
        if(!$findUser){
            return [
                'message' => 'erreur : token invalide',
                'status' => 'success'
            ];
        } else {
            if(isset($requestDatas['newValue'])){
                $plainTextPassword = $requestDatas[0];
                $hashedPassword = $this->passwordHasher->hashPassword($user, $plainTextPassword);
                $findUser->setPassword($hashedPassword);
            }
            return [
                'message' => 'success : mot de passe changé avec succés',
                'status' => 'success'
            ];
        }
    }

}
