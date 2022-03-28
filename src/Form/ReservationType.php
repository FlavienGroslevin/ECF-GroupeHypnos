<?php

namespace App\Form;

use App\Entity\HotelRooms;
use App\Entity\Hotels;
use App\Entity\ReservationRooms;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('start_date',  DateType::class, [
                'label' => "Date d'arrivée",
                'widget' => 'single_text',
                'html5' => false,
                'empty_data' => null,
                'attr' => ["class" => 'start'],
            ])
            ->add('end_date', DateType::class, [
                'label' => "Date de départ",
                'widget' => 'single_text',
                'html5' => false,
                'empty_data' => null,
                'attr' => ["class" => 'end'],
            ])
            ->add('hotels', EntityType::class, [
                'label' => "Choix de l'hôtel",
                'class' => Hotels::class,
                'placeholder' => 'Choisir un hôtel'
            ])
        ;
        $formModifier = function (FormInterface $form, Hotels $hotels = null){
            $hotelRooms = null === $hotels ? [] : $hotels->getHotelRooms();

            $form->add('hotelRooms', EntityType::class, [
                'class' => HotelRooms::class,
                'choices' => $hotelRooms,
                'label' => 'Choix de la suite',
            ]);
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier){
             $data = $event->getData();
             $formModifier($event->getForm(), $data->getHotelRooms());
            }
        );

        $builder->get('hotels')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                $hotel = $event->getForm()->getData();
                $formModifier($event->getForm()->getParent(), $hotel);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReservationRooms::class,
        ]);
    }
}
