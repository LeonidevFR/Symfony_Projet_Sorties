<?php

namespace App\Controller\Admin;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Outings;
use App\Entity\Status;
use App\Entity\User;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="app_")
 */
class DashboardController extends AbstractDashboardController
{
    protected $userRepository;

    public function __construct(
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $users = $this->userRepository->findAll();
        $admin = 0;
        foreach($users as $user) {
            foreach ($user->getRoles() as $roles) {
                if(str_contains($roles,'ROLE_ADMIN'))
                    $admin++;
            }
        }
        return $this->render('admin/dashboard.html.twig', [
            'users' => $users,
            'admins' => $admin
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Admin panel');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Accueil', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class);
        yield MenuItem::linkToCrud('Sorties', 'fas fa-hiking', Outings::class);
        yield MenuItem::linkToCrud('Campus', 'fas fa-laptop-house', Campus::class);
        yield MenuItem::linkToCrud('Villes', 'fas fa-city', City::class);
        yield MenuItem::linkToCrud('Status', 'fas fa-unlock', Status::class);
    }
}
