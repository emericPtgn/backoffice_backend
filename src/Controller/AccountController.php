<?php

namespace App\Controller;

use App\Form\RegistrationType;
use App\Form\Model\Registration;
use Symfony\Component\Form\FormError;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use MongoDB\Driver\Exception\BulkWriteException;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountController extends AbstractController
{
    private DocumentManager $dm;

    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
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
            // hash the password (based on the security.yaml config for the $user class)
            $hashedPassword = $passwordHasher->hashPassword($user,$plaintextPassword);
            $user->setPassword($hashedPassword);
            $this->dm->persist($user);
            $this->dm->flush();
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
}}