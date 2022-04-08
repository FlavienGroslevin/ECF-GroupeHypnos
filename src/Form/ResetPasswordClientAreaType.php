<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetPasswordClientAreaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('password', PasswordType::class,[
                'label' => 'Mot de passe actuel',
                'attr' => [
                    'placeholder' => 'Mot de passe actuel'
                ]
            ])
            ->add('newPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe et la confirmation doivent être identique.',
                'label' => 'Votre nouveau mot de passe',
                'first_options' => [ 'label' => 'Nouveau mot de passe', 'attr' => ['placeholder' => 'Merci de saisir votre mot de passe']],
                'second_options' => [ 'label' => 'Confirmez votre nouveau mot de passe', 'attr' => ['placeholder' => 'Merci de confirmer votre mot de passe']]
            ])
            ->add('submit', SubmitType::class,[
                'label' => 'Changer de mot de passe',
                'attr' => [
                    'class' => 'btn-info'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([

        ]);
    }
}
