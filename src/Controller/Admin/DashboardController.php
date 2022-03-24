<?php

namespace App\Controller\Admin;

use App\Entity\HotelRooms;
use App\Entity\Hotels;
use App\Entity\Images;
use App\Entity\Users;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{

    public function __construct(private AdminUrlGenerator $adminUrlGenerator) {

    }
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {

        $url = $this->adminUrlGenerator->setController(GerantCrudController::class)->generateUrl();
        return $this->redirect($url);

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

        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
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
