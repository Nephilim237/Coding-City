<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Form\PostFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/post', name: 'post_list')]
    public function index(): Response
    {
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }

    #[Route('admin/post/add', name: 'post_add')]
    public function add(Request $request, EntityManagerInterface $manager): Response
    {
        $post = new Post();
        $form = $this->createForm(PostFormType::class, $post);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post
                ->setAuthor($this->getUser())
                ->setActive(false)
            ;

            $manager->persist($post);
            $manager->flush();
            $this->addFlash('success', 'Votre post est en attente de validation, Vous serez informé lors de sa publication.');
            return $this->redirectToRoute('post_list');
        }
        return $this->render('admin/post/add.html.twig', [
            'post_form' => $form->createView()
        ]);
    }
}
