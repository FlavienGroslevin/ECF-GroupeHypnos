<?php

namespace App\Controller\Admin;

use App\Entity\Hotels;
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

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Hypnos');
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
    }
}
