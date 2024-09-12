<?php

namespace App\Service;
use App\Document\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class UserService {
    private DocumentManager $dm;
    private UserPasswordHasherInterface $passwordHasher;
    private UserRepository $userRepo;
    private MailerInterface $mailer;
    private LoggerInterface $logger;
    private PasswordAuthenticatedUserInterface $user;
    public function __construct(DocumentManager $dm, 
    UserPasswordHasherInterface $passwordHasher, 
    UserRepository $userRepo, 
    MailerInterface $mailer,
    LoggerInterface $logger, 
    )
    {
        $this->dm = $dm;
        $this->passwordHasher = $passwordHasher;
        $this->userRepo = $userRepo;
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    public function addUser(array $requestDatas)
    {
        // Validation des données
        if (!isset($requestDatas['newMail']) || !filter_var($requestDatas['newMail'], FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email address provided.');
        }
    
        // Recherche d'un utilisateur existant avec le même email
        $existUser = $this->userRepo->findOneBy(['email' => $requestDatas['newMail']]);
        if ($existUser) {
            if($existUser->isVerified() === false){
                if(isset($requestDatas['newRoles'])){
                    $existUser->setRoles($requestDatas['newRoles']);
                }
                return $existUser;
            } else {
                return $existUser; // Manquait un return ici
            }
        }
    
        // Création d'un nouvel utilisateur
        $user = new User();
    
        // Attribution des données au nouvel utilisateur
        $user->setEmail($requestDatas['newMail']);
        $user->setVerificationToken($this->generateToken());
    
        if (isset($requestDatas['newRoles']) && is_array($requestDatas['newRoles'])) {
            $user->setRoles($requestDatas['newRoles']);
        }
    
        $user->setIsVerified(false);
    
        try {
            // Persistance du nouvel utilisateur dans la base de données
            $this->dm->persist($user);
            $this->dm->flush();
            $this->sendMailConfirmNewUser($user->getEmail(), $user->getVerificationToken());
        } catch (\Exception $e) {
            // Gestion des erreurs lors de l'enregistrement en base de données
            throw new \RuntimeException('Failed to save the user: ' . $e->getMessage());
        }
    
        return $user;
    }

    public function checkToken(User $user){
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
        return $token;
    }

    public function sendMailConfirmNewUser(string $email, string $token){
        $validateUrl = "https://pro.testdwm.fr/signin/$token";
        // Préparation de l'email
        $email = (new Email())
            ->from('dataisbeautyfull@gmx.com')
            ->to($email)
            ->subject('Join Live Event')
            ->html("<p>You are invited to join Live Event Team, click here to confirm: <a href=\"$validateUrl\">Join the team</a></p>");
        // Envoi de l'email
        $this->mailer->send($email);
    }
    public function confirmUser(string $password, string $token){
        $user = $this->userRepo->findOneBy(['verificationToken' => $token]);
        if(!$user){
            return [
                'status' => 'invalid token',
                'message' => 'user not found, perhapse invalid token, contact admin'
            ];
        } else {
            $dateNow = new \DateTime();
            $isExpired = $dateNow > $user->getVerificationTokenExpiresAt();
            if($isExpired){
                return [
                    'status' => 'expired token',
                    'message' => 'expired token, contact admin to renew process'
                ];
            } else {
                $plainPassword = $password;
                $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword);
                $user->setIsVerified(true);
                $this->dm->persist($user);
                $this->dm->flush();
                return [
                    'status' => 'success',
                    'message' => 'registered success ✅'
                ];}}}

    public function deleteUser(string $id)
    {
        $user = $this->userRepo->find($id);
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
        $user = $this->userRepo->find($id);
        if (!$user) {
            return ['message' => 'this user does not exist'];
        }
    
        // Vérification pour newRole : ne traiter que si non null et non vide
        if (!empty($requestDatas['newRole'])) {
            $rolesDatas = is_array($requestDatas['newRole']) ? $requestDatas['newRole'] : [$requestDatas['newRole']];
            $user->setRoles($rolesDatas);
            $this->dm->persist($user);
            $this->dm->flush();
        }
    
        // Vérification pour checkPassword et newPassword : ne traiter que si non null et non vides
        if (!empty($requestDatas['checkPassword']) && !empty($requestDatas['newPassword'])) {
            $plainTextCheckPassword = $requestDatas['checkPassword'];
            $plainTextNewPassword = $requestDatas['newPassword'];
            $matchedPassword = $this->passwordHasher->isPasswordValid($user, $plainTextCheckPassword);
    
            if ($matchedPassword) {
                $user->setPassword($this->passwordHasher->hashPassword($user, $plainTextNewPassword));
                $this->dm->persist($user);
            } else {
                return [
                    'message' => 'password unknown, please try again',
                    'status' => 'invalid password'
                ];
            }
        }
    
        $this->dm->flush();
        return [
            'status' => 'success',
            'data' => $user
        ];
    }
    
    public function getUsers()
    {
        $users = $this->userRepo->findAll();
        if(count($users) === 0){
            return ['message' => 'no users found, add your first user!'];
        }
        return $users;
    }
    public function getThisUser(string $id){
        $user = $this->userRepo->find($id);
        if(!$user){
            return [
                'message' => 'no users found',
                'status' => 'not found'
            ];
        }
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
        $resetPassUrl = "https://pro.testdwm.fr/reset-password/$token";
    
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
        $findUser = $this->userRepo->findOneBy(['verificationToken' => $token]);
        if(!$findUser){
            return [
                'message' => 'erreur : token invalide',
                'status' => 'success'
            ];
        } else {
            if($password){
                $plainTextPassword = $password;
                $hashedPassword = $this->passwordHasher->hashPassword($findUser, $plainTextPassword);
                $findUser->setPassword($hashedPassword);
            }
            return [
                'message' => 'success : mot de passe changé avec succés',
                'status' => 'success'
            ];
        }
    }
    public function sendChangeEmail(User $user, string $email){
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
            }
        } else {
            // Générer un nouveau token s'il n'y en a pas
            $token = bin2hex(random_bytes(32));
            $user->setVerificationToken($token);
            // Définir la date d'expiration du token
            $user->setVerificationTokenExpiresAt((new \DateTime())->modify('+1 hour'));
        }
    
        // Enregistrer l'email temporairement dans le champ newEmail
        $user->setNewEmail($email);
        $this->dm->persist($user);
        $this->dm->flush();
    
        // Construction de l'URL de confirmation d'email
        $confirmUrl = "https://api.testdwm.fr/api/user/confirm-email/$token";
    
        // Préparation de l'email
        $emailMessage = (new Email())
            ->from('dataisbeautyfull@gmx.com')
            ->to($email)
            ->subject('Confirm your email address change')
            ->html("<p>To confirm the change of your email address, please click the link: <a href=\"$confirmUrl\">Confirm Email Change</a></p>");
    
        // Envoi de l'email
        $this->mailer->send($emailMessage);
    
        return [
            'ok' => "Confirmation email sent.",
            'status' => "success"
        ];
    }
    public function getUserByToken(string $token){
        $user = $this->userRepo->findOneBy(['verificationToken' => $token]);
        if(!$user){
            return 'no user matches this token';
        } else {
            return $user;
        }
    }
    public function confirmEmail(string $token){
        $user = $this->getUserByToken($token);
        if (!$user) {
            return [
                'status' => 'invalid',
                'message' => 'invalid token'
            ];
        }
        $dateNow = new \DateTime();
        if ($dateNow > $user->getVerificationTokenExpiresAt()) {
            return [
                'status' => 'expired',
                'message' => 'token expired'
            ];
        }
        $user->setEmail($user->getNewEmail()); // Assure-toi de stocker la nouvelle email dans un champ temporaire, ex: pendingEmail
        $user->setVerificationToken(null); // Invalide le token après utilisation
        $user->setVerificationTokenExpiresAt(null); // Supprime la date d'expiration
        $this->dm->persist($user);
        $this->dm->flush();

        return 
        [
            'status' => 'success', 
            'message' => 'Email updated successfully.'
        ];
    
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
        $this->dm->persist($user);
        $this->dm->flush();
    }
    /**
     * @inheritDoc
     */
    public function getPassword(): string|null {
    }

    public function generateToken(){
        $token = bin2hex(random_bytes(32));
        return $token;
    }
}