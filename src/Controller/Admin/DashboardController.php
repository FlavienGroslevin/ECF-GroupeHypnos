<?php

namespace App\Controller\Admin;

use App\Entity\HotelRooms;
use App\Entity\Hotels;
use App\Entity\Images;
use App\Entity\ReservationRooms;
use App\Entity\Users;
use App\Repository\HotelsRepository;
use App\Repository\ReservationRoomsRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class DashboardController extends AbstractDashboardController
{

    #[Route('/admin', name: 'admin')]
    public function Dashboard(ChartBuilderInterface $chartBuilder, HotelsRepository $hotelsRepository, ReservationRoomsRepository $reservationRoomsRepository): Response
    {
        $hotels = $hotelsRepository->findAll();
        $data = [];
        $labels = [];

        foreach ($hotels as $hotel){
            $labels[] = $hotel->getName();
            $data[] = count($hotel->getReservationRooms());
        }

        $chart = $chartBuilder->createChart(Chart::TYPE_BAR);

        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => "Nombre de réservation par hôtel",
                    'backgroundColor' => '#DE9151',
                    'borderColor' => '#FEEAA6',
                    'data' => $data,
                ],
            ],
        ]);

        return $this->render('admin/dashboard.html.twig', [
            'chart' => $chart,
        ]);

    }

    public function configureDashboard(): Dashboard
    {
        /**
         * @var Users $user
         */
        $user = $this->getUser();
        if ($user->getRole() == 'ROLE_GERANT'){
            return Dashboard::new()
                ->setTitle((string) $user->getNameHotel());
        }
        return Dashboard::new()
            ->setTitle('Groupe Hypnos');

    }
    public function configureMenuItems(): iterable
    {

        yield MenuItem::linkToDashboard("Page d'accueil", 'fa fa-home');
        yield MenuItem::linkToCrud('Réservation', 'fa fa-ticket', ReservationRooms::class)
            ->setController(ReservationRoomsCrudController::class);
        yield MenuItem::subMenu('Utilisateur', 'fa fa-user-circle')
            ->setPermission('ROLE_ADMIN')
            ->setSubItems([
                MenuItem::linkToCrud('Gérants', 'fa fa-users', Users::class)
                    ->setController(GerantCrudController::class),
                MenuItem::linkToCrud('Clients', 'fa fa-user', Users::class)
                    ->setController(UserCrudController::class),
                MenuItem::linkToCrud('Administrateurs', 'fa fa-user-md', Users::class)
                    ->setController(AdminCrudController::class),
            ]);
        yield MenuItem::linkToCrud('Établissement', 'fa fa-address-book', Hotels::class)
            ->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('Chambres', 'fa fa-bed', HotelRooms::class)
            ->setPermission('ROLE_ADMIN')
            ->setController(HotelRoomsAdminsCrudController::class);
        yield MenuItem::linkToCrud('Chambres', 'fa fa-bed', HotelRooms::class)
            ->setController(HotelRoomsCrudController::class)
            ->setPermission('ROLE_GERANT');
        yield MenuItem::linkToCrud('Images', 'fa fa-image', Images::class);
    }
}
