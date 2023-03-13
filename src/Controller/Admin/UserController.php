<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\AdminAddUserFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('admin/users', name: 'admin_users')]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('admin/user/index.html.twig', [
            'users' => $userRepository->getSomeUsers(10)
        ]);
    }

    /**
     * @param User $user
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('admin/users/enable/{user}', name: 'admin_user_enable')]
    public function enableUser(User $user, EntityManagerInterface $entityManager): Response
    {
        $user->setIsVerified(!$user->isVerified());
        $entityManager->persist($user);
        $entityManager->flush();

        return new Response('true');
    }

    /**
     * @param User $user
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('admin/users/remove/{user}', name: 'admin_user_remove')]
    public function removeUser(User $user, EntityManagerInterface $entityManager): Response
    {
        $username = $user->getUserNaming()->getFullUserName() ?? $user->getEmail();
        $entityManager->remove($user);
        $entityManager->flush();
        $this->addFlash('admin_home_user', "Suppression de {$username} terminée.");
        return $this->redirectToRoute('admin_users');
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @param MailerInterface $mailer
     * @return Response
     * @throws TransportExceptionInterface
     */
    #[Route('admim/users/add', name: 'admin_user_add')]
    public function addUser(EntityManagerInterface $entityManager, Request $request, UserPasswordHasherInterface $userPasswordHasher,
                            MailerInterface $mailer):
    Response
    {
        $user = new User();
        $form = $this->createForm(AdminAddUserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $user
                ->setIsVerified(true)
                ->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    '1234567890'
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            $email = (new TemplatedEmail())
                ->from(new Address('no-reply@theknowledge.com', 'The Knowledge'))
                ->to($user->getEmail())
                ->subject('Invitation de "The Knowledge"')
                ->context([
                    'role' => $user->getRoles()[0],
                ])
                ->htmlTemplate('admin/user/joined_us.html.twig')
            ;

            $mailer->send($email);

            $this->addFlash('admin_home_user', "Une invitation a été envoyé à {$user->getEmail()}.");

            return $this->redirectToRoute('admin_users');
        }
        return $this->render('admin/user/add.html.twig', [
            'adminAddUserForm' => $form->createView(),
        ]);
    }
}
