<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class,[
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Votre prénom'
                ]
            ])
            ->add('lastname', TextType::class,[
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Votre nom'
                ]
            ])
            ->add('email', EmailType::class,[
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Votre email'
                ]
            ])
            ->add('subject', ChoiceType::class, [
                'label' => 'Sujet',
                'placeholder' => 'Choisir un sujet',
                'choices' => [
                    'Je souhaite poser une réclamation' => 'Je souhaite poser une réclamation',
                    'Je souhaite commander un service supplémentaire' => 'Je souhaite commander un service supplémentaire',
                    'Je souhaite en savoir plus sur une suite' => 'Je souhaite en savoir plus sur une suite',
                    "J'ai un souci avec l'application" => "J'ai un souci avec l'application"
                ]
            ])
            ->add('message', TextareaType::class,[
                'label' => 'Message',
                'attr' => [
                    'rows' => "6",
                    'placeholder' => 'Votre message...'
                ]
            ])
            ->add('Envoyer', SubmitType::class, [
                'attr' => [
                    'class' => 'btn-info w-100'
                ]
            ])
            ->add('Effacer', ResetType::class,[
                'attr' => [
                    'class' => 'btn-success mx-4 w-100',
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
