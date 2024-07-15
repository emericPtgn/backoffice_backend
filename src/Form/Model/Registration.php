<?php

namespace App\Form\Model;

use App\Document\User;
use Symfony\Component\Validator\Constraints as Assert;

class Registration
{
    #[Assert\Type(type: User::class)]
    protected $user;

    #[Assert\NotBlank]
    #[Assert\IsTrue]
    protected $termsAccepted;

    public function __construct()
    {
        $this->user = new User(); // Initialisation de l'objet User
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getUser(): User 
    {
        return $this->user;
    }

    public function getTermsAccepted(): bool
    {
        return $this->termsAccepted;
    }

    public function setTermsAccepted(bool $termsAccepted): void
    {
        $this->termsAccepted = $termsAccepted;
    }
}
