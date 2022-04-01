<?php

namespace App\Form;

use App\Entity\HotelRooms;
use App\Entity\Hotels;
use App\Entity\ReservationRooms;
use App\Repository\HotelRoomsRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public RequestStack $requestStack;

    public  function __construct(RequestStack $requestStack){
        $this->requestStack = $requestStack;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('start_date',  DateType::class, [
                'label' => "Date d'arrivée",
                'widget' => 'single_text',
                'html5' => false,
                'empty_data' => null,
                'format' => 'dd/MM/yyyy',
                'attr' => [
                    "class" => 'start',
                    "autocomplete" => 'off'
                ],
            ])
            ->add('end_date', DateType::class, [
                'label' => "Date de départ",
                'widget' => 'single_text',
                'html5' => false,
                'empty_data' => null,
                'format' => 'dd/MM/yyyy',
                'attr' => [
                    "class" => 'end',
                    "autocomplete" => 'off'
                ],
            ])
            ->add('hotels', EntityType::class, [
                'label' => "Choix de l'hôtel",
                'class' => Hotels::class,
                'placeholder' => 'Choisir un hôtel',
                'choice_attr' => function($hotel) {
                    $session = $this->requestStack->getSession()->get('hotel');
                    $attr = [];
                    if ($hotel->getId() == $session){
                        $attr['selected'] = 'selected';
                    }
                    return $attr;
                }
            ])
            ->add('Rechercher', SubmitType::class,[
                'label' => "Vérifier la disponibilité",
                'attr' => ['class' => 'btn btn-info']
            ]);

            $formModifier = function (FormInterface $form, Hotels $hotels = null) {
                $hotelRooms = null === $hotels ? [] : $hotels->getId();
                $hotel = $this->requestStack->getSession()->get('hotel');
                $form->add('hotelRooms', EntityType::class, [
                    'class' => HotelRooms::class,
                    'label' => 'Choix de la suite',
                    'query_builder' => function (HotelRoomsRepository $repository) use ($hotelRooms, $hotel){
                        if ($this->requestStack->getSession()->get('hotelRoom')){
                            $render = $repository->createQueryBuilder('h')
                                ->andWhere('h.hotels = :hotel')
                                ->setParameter('hotel', $hotel);
                        } else {
                            $render = $repository->createQueryBuilder('h')
                                ->andWhere('h.hotels = :hotel')
                                ->setParameter('hotel', $hotelRooms);
                        }
                        return $render;
                    },
                    'choice_attr' => function ($hotel) {
                        $session = $this->requestStack->getSession()->get('hotelRoom');
                        $attr = [];
                        if ($hotel->getId() == $session) {
                            $attr['selected'] = 'selected';
                        }
                        return $attr;
                    }
                ]);
            };
            $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                $data = $event->getData()->getHotelRooms();
                $formModifier($event->getForm(), $data);
            });

            $builder->get('hotels')->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) use ($formModifier){
                    $this->requestStack->getSession()->set('hotelRoom', "");
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
