<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController
{
    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    #[Route('/admin', name: 'admin')]
    public function index(UserRepository $userRepository): Response
    {
        $countAllUsers = $userRepository->countAllUsers();
        return $this->render('admin/home/index.html.twig', [
            'countAllUsers' => $countAllUsers
        ]);
    }
}
