<?php

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use App\Document\User;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ResetPassWordMailType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options):void 
    {
        $builder
        ->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'first_options' => [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Your password must be at least {{ limit }} characters long',
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
                        'message' => 'Your password must contain at least one lowercase letter, one uppercase letter, one digit, and one special character.',
                    ]),
                ],
                'label' => 'Password',
            ],
            'second_options' => [
                'label' => 'Confirm Password',
            ],
            'invalid_message' => 'The password fields must match.',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}