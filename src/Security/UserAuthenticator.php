<?php

// src/Security/UserAuthenticator.php

namespace App\Security;

use App\Document\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

class UserAuthenticator extends AbstractLoginFormAuthenticator
{
    // ... autres méthodes requises

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');
        $password = $request->request->get('password', '');

        return new Passport(
            new UserBadge($email, function(User $user) {
                if (!$user->isVerified()) {
                    throw new CustomUserMessageAuthenticationException('Veuillez vérifier votre email avant de vous connecter.');
                }
                return $user;
            }),
            new PasswordCredentials($password)
        );
    }

    // ... autres méthodes requises
    /**
     * @inheritDoc
     */
    protected function getLoginUrl(Request $request): string {
    }
    
    /**
     * @inheritDoc
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): Response|null {
    }
}