<?php

namespace App\Service;
use App\Document\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService {
    private DocumentManager $dm;
    private UserPasswordHasherInterface $passwordHasher;
    private UserRepository $userRepo;
    private MailerInterface $mailer;
    private LoggerInterface $logger;
    public function __construct(DocumentManager $dm, 
    UserPasswordHasherInterface $passwordHasher, 
    UserRepository $userRepo, 
    MailerInterface $mailer,
    LoggerInterface $logger
    )
    {
        $this->dm = $dm;
        $this->passwordHasher = $passwordHasher;
        $this->userRepo = $userRepo;
        $this->mailer = $mailer;
        $this->logger = $logger;
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

    public function getThisUser(string $id) : User {
        $this->logger->info('id:', ['value' => $id]);
        $user = $this->userRepo->getThisUser($id);
        return $user;
    }

    public function sendResetPasswordEmail(User $user) 
    {
        $dateNow = new \DateTime();
        $token = $user->getVerificationToken();
        
        if ($token) {
            $expireAt = $user->getVerificationTokenExpiresAt();
            $isExpired = $dateNow > $expireAt;
    
            if ($isExpired) {
                // Générer un nouveau token si l'ancien est expiré
                $token = bin2hex(random_bytes(32));
                $user->setVerificationToken($token);
                // Mettez à jour la date d'expiration du token
                $user->setVerificationTokenExpiresAt((new \DateTime())->modify('+1 hour'));
                $this->dm->persist($user);
                $this->dm->flush();
            }
        } else {
            // Générer un nouveau token s'il n'y en a pas
            $token = bin2hex(random_bytes(32));
            $user->setVerificationToken($token);
            // Définir la date d'expiration du token
            $user->setVerificationTokenExpiresAt((new \DateTime())->modify('+1 hour'));
            $this->dm->persist($user);
            $this->dm->flush();
        }
    
        // Construction de l'URL de réinitialisation de mot de passe
        $resetPassUrl = "https://localhost:3000/reset-password/$token";
    
        // Préparation de l'email
        $email = (new Email())
            ->from('dataisbeautyfull@gmx.com')
            ->to($user->getEmail())
            ->subject('Reset Password')
            ->html("<p>Click here to reset your password: <a href=\"$resetPassUrl\">Reset my password</a></p>");
    
        // Envoi de l'email
        $this->mailer->send($email);
    
        return [
            'ok' => "Reset password email sent.",
            'status' => "success"
        ];
    }
    

    public function resetPassword(string $password, string $token){
        $user = $this->dm->getRepository(User::class)->findOneBy(['verificationToken' => $token]);
        if($password){
            $plainPassword = $password;
            $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);
            return ['status' => 'success'];
        }
    }

}