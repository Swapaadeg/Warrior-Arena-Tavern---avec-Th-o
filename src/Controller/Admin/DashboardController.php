<?php

namespace App\Controller\Admin;

use App\Entity\Characters;
use App\Entity\Roles;
use App\Entity\Types;
use App\Entity\TypesArmes;
use App\Entity\User;
use App\Entity\Weapons;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

#[IsGranted('ROLE_ADMIN')]
#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function index(): Response
    {
        $charactersCount = $this->em->getRepository(Characters::class)->count([]);
        $usersCount = $this->em->getRepository(User::class)->count([]);
        $rolesCount = $this->em->getRepository(Roles::class)->count([]);
        $typesCount = $this->em->getRepository(Types::class)->count([]);

        // Statistiques par rôles
        $tankCount = $this->em->createQueryBuilder()
            ->select('COUNT(c.id)')
            ->from(Characters::class, 'c')
            ->join('c.role', 'r')
            ->where('r.name = :role')
            ->setParameter('role', 'Tank')
            ->getQuery()
            ->getSingleScalarResult();

        $dpsCount = $this->em->createQueryBuilder()
            ->select('COUNT(c.id)')
            ->from(Characters::class, 'c')
            ->join('c.role', 'r')
            ->where('r.name = :role')
            ->setParameter('role', 'DPS')
            ->getQuery()
            ->getSingleScalarResult();

        $healCount = $this->em->createQueryBuilder()
            ->select('COUNT(c.id)')
            ->from(Characters::class, 'c')
            ->join('c.role', 'r')
            ->where('r.name = :role')
            ->setParameter('role', 'Heal')
            ->getQuery()
            ->getSingleScalarResult();

        // Statistiques par types
        $taverneCount = $this->em->createQueryBuilder()
            ->select('COUNT(c.id)')
            ->from(Characters::class, 'c')
            ->join('c.type', 't')
            ->where('t.name = :type')
            ->setParameter('type', 'Taverne')
            ->getQuery()
            ->getSingleScalarResult();

        $academieCount = $this->em->createQueryBuilder()
            ->select('COUNT(c.id)')
            ->from(Characters::class, 'c')
            ->join('c.type', 't')
            ->where('t.name = :type')
            ->setParameter('type', 'Académie')
            ->getQuery()
            ->getSingleScalarResult();

        $ombreCount = $this->em->createQueryBuilder()
            ->select('COUNT(c.id)')
            ->from(Characters::class, 'c')
            ->join('c.type', 't')
            ->where('t.name = :type')
            ->setParameter('type', 'Ombre')
            ->getQuery()
            ->getSingleScalarResult();

        return $this->render('admin/index.html.twig', [
            'charactersCount' => $charactersCount,
            'usersCount' => $usersCount,
            'rolesCount' => $rolesCount,
            'typesCount' => $typesCount,
            // Stats par rôles
            'tankCount' => $tankCount,
            'dpsCount' => $dpsCount,
            'healCount' => $healCount,
            // Stats par types
            'taverneCount' => $taverneCount,
            'academieCount' => $academieCount,
            'ombreCount' => $ombreCount,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('⚔️ Warrior Arena Tavern');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('Navigation');
        yield MenuItem::linkToUrl('🏠 Retourner sur le site', 'fas fa-external-link-alt', '/');
        yield MenuItem::section('Gestion des entités');
        yield MenuItem::linkToCrud('🧙‍♂️ Personnages', 'fas fa-users', Characters::class);
        yield MenuItem::linkToCrud('🛡️ Rôles', 'fas fa-shield-alt', Roles::class);
        yield MenuItem::linkToCrud('🏛️ Types', 'fas fa-building', Types::class);
        yield MenuItem::section('Armes');
        yield MenuItem::linkToCrud('⚔️ Armes', 'fas fa-sword', Weapons::class);
        yield MenuItem::linkToCrud('🗡️ Types d\'armes', 'fas fa-list', TypesArmes::class);
        yield MenuItem::section('Utilisateurs');
        yield MenuItem::linkToCrud('👤 Utilisateurs', 'fas fa-user', User::class);
    }
}
