<?php

namespace App\Controller;

use App\Entity\AboutUser;
use App\Entity\Image;
use App\Entity\User;
use App\Entity\UserNaming;
use App\Form\AboutUserFormType;
use App\Form\ProfileImageFormType;
use App\Form\UserNamingFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserProfileController extends AbstractController
{
    #[Route('/profile/{id}', name: 'app_user_profile')]
    public function index(UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBy(['id' => $this->getUser()]);
        return $this->render('user_profile/index.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/profile/add/bio/{id}', name: 'add_user_bio')]
    public function addUserBio(Request $request, EntityManagerInterface $entityManager): Response
    {
        $aboutUser = new AboutUser();
        $user = new User();
        $form = $this->createForm(AboutUserFormType::class, $aboutUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $aboutUser
                ->setUser($this->getUser())
            ;
            $user
                ->setUpdatedAt(new \DateTimeImmutable())
            ;

            $entityManager->persist($aboutUser);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_profile', ['id' => $this->getUser()->getId()]);
        }

        return $this->render('user_profile/user_bio.html.twig', [
            'AboutUserForm' => $form->createView(),
        ]);
    }

    #[Route('/profile/update/bio/{user}', name: 'update_user_bio')]
    public function updateUserBio(User $user, AboutUser $aboutUser, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AboutUserFormType::class, $aboutUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->persist($user);
            $entityManager->persist($aboutUser);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_profile', ['id' => $this->getUser()->getId()]);
        }

        return $this->render('/user_profile/user_bio.html.twig', [
            'AboutUserForm' => $form->createView(),
        ]);
    }

    #[Route('/profile/add/naming/{id}', name: 'add_user_naming')]
    public function addUserNaming(User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        $userNaming = new UserNaming();
        $form = $this->createForm(UserNamingFormType::class, $userNaming);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user
                ->setUpdatedAt(new \DateTimeImmutable())
            ;
            $userNaming
                ->setUser($this->getUser())
            ;
            $entityManager->persist($user);
            $entityManager->persist($userNaming);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_profile', [
                'id' => $this->getUser()->getId()
            ]);
        }

        return $this->render('user_profile/user_naming.html.twig', [
            'userNamingForm' => $form->createView(),
        ]);
    }

    #[Route('/profile/update/naming/{user}', name: 'update_user_naming')]
    public function updateUserNaming(UserNaming $userNaming, User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserNamingFormType::class, $userNaming);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user
                ->setUpdatedAt(new \DateTimeImmutable())
            ;
            $entityManager->persist($user);
            $entityManager->persist($userNaming);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_profile', [
                'id' => $this->getUser()->getId()
            ]);
        }

        return $this->render('user_profile/user_naming.html.twig', [
            'userNamingForm' => $form->createView(),
        ]);
    }

    #[Route('/profile/add/profile-image/{user}', name: 'add_user_profile')]
    public function addUserProfileImage(User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        $image = new Image();
        $form = $this->createForm(ProfileImageFormType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user
                ->setUpdatedAt(new \DateTimeImmutable())
            ;
            $image
                ->setUser($user)
            ;
            $entityManager->persist($user);
            $entityManager->persist($image);
            $entityManager->flush();

            $this->addFlash('profile_update', 'Votre photo de profil a été ajoutée avec succès.');
            return $this->redirectToRoute('app_user_profile', [
               'id' => $this->getUser()->getId(),
            ]);
        }
        return $this->render('user_profile/user_profile.html.twig', [
            'userProfileImageForm' => $form->createView(),
        ]);
    }

    #[Route('/profile/update/profile-image/{user}', name: 'update_user_profile')]
    public function updateUserProfileImage(User $user, Image $image, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProfileImageFormType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user
                ->setUpdatedAt(new \DateTimeImmutable())
            ;
            $image->setImage($form->get('imageFile')->getData());
            $entityManager->persist($image);
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('profile_update', 'Votre photo de profil a été modifiée avec succès.');
            return $this->redirectToRoute('app_user_profile', [
               'id' => $this->getUser()->getId(),
            ]);
        }
        return $this->render('user_profile/user_profile.html.twig', [
            'userProfileImageForm' => $form->createView(),
        ]);
    }
}
