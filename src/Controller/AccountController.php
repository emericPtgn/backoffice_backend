<?php


namespace App\Controller;


use App\Document\User;
use App\Form\RegistrationType;
use App\Form\Model\Registration;
use Symfony\Component\Mime\Email;
use Symfony\Component\Form\FormError;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use MongoDB\Driver\Exception\BulkWriteException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AccountController extends AbstractController
{
    private DocumentManager $dm;
    private MailerInterface $mailer;


    public function __construct(DocumentManager $dm, MailerInterface $mailer)
    {
        $this->dm = $dm;
        $this->mailer = $mailer;
    }
    

    #[Route('/account', name: 'app_registration')]
public function registerAction(Request $request, UserPasswordHasherInterface $passwordHasher)
{
    $registration = new Registration();
    $form = $this->createForm(RegistrationType::class, $registration);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        try {
            $user = $registration->getUser();
            $plaintextPassword = $user->getPassword();
            $hashedPassword = $passwordHasher->hashPassword($user, $plaintextPassword);
            $user->setPassword($hashedPassword);

            // Générer un token de vérification
            $verificationToken = bin2hex(random_bytes(32));
            $user->setVerificationToken($verificationToken);
            $user->setVerificationTokenExpiresAt(new \DateTime('+1 hour'));

            $this->dm->persist($user);
            $this->dm->flush();

            // Envoyer l'email de vérification
            $this->sendVerificationEmail($user);

            $this->addFlash('success', 'Inscription réussie. Veuillez vérifier votre email pour activer votre compte.');
            return $this->redirectToRoute('app_homepage');

        } catch (BulkWriteException $e) {
            if (strpos($e->getMessage(), 'E11000 duplicate key error') !== false) {
                $form->get('user')->get('email')->addError(new FormError('Cet email est déjà utilisé.'));
            } else {
                throw $e;
            }
        }
    }

    return $this->render('account/index.html.twig', [
        'controller_name' => 'AccountController',
        'form' => $form->createView()
    ]);
}


    private function sendVerificationEmail(User $user): void
    {
        $verificationUrl = $this->generateUrl('app_verify_email', [
            'token' => $user->getVerificationToken(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $email = (new Email())
            ->from('dataisbeautyfull@gmx.com')
            ->to($user->getEmail())
            ->subject('Vérifiez votre adresse email')
            ->html("<p>Cliquez sur ce lien pour vérifier votre email : <a href=\"{$verificationUrl}\">Vérifier mon email</a></p>");

        $this->mailer->send($email);
    }

    #[Route('/verify-email/{token}', name: 'app_verify_email')]
    public function verifyEmail(string $token): Response
    {
        $user = $this->dm->getRepository(User::class)->findOneBy(['verificationToken' => $token]);

        if (!$user) {
            throw $this->createNotFoundException('Token de vérification invalide.');
        }

        if ($user->getVerificationTokenExpiresAt() < new \DateTime()) {
            throw $this->createNotFoundException('Le token de vérification a expiré.');
        }

        $user->setIsVerified(true);
        $user->setVerificationToken(null);
        $user->setVerificationTokenExpiresAt(null);

        $this->dm->flush();

        $this->addFlash('success', 'Votre email a été vérifié. Vous pouvez maintenant vous connecter.');

        return $this->redirect('https://localhost:3000/login');
    }
};