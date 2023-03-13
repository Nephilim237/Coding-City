<?php

namespace App\Controller\Admin;

use ACSEO\TypesenseBundle\Finder\TypesenseQuery;
use App\Entity\Post;
use App\Form\PostFormType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    private $postFinder;

    public function __construct($postFinder)
    {
        $this->postFinder = $postFinder;
    }

    #[Route('/admin/posts', name: 'post_list')]
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('admin/post/index.html.twig', [
            'posts' => $postRepository->getSomePosts(10),
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
                ->setActive(false);

            $manager->persist($post);
            $manager->flush();
            $this->addFlash('admin_home_post', 'Votre post est en attente de validation, Vous serez informé lors de sa publication.');
            //TODO Penser à notifier l'admin lors de la publication d'un nouveau post
            return $this->redirectToRoute('post_list');
        }
        return $this->render('admin/post/add.html.twig', [
            'post_form' => $form->createView()
        ]);
    }


    #[Route('admin/post/edit/{slug}', name: 'post_edit')]
    public function edit(Request $request, EntityManagerInterface $manager, Post $post): Response
    {
        $form = $this->createForm(PostFormType::class, $post);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($post);
            $manager->flush();
            $this->addFlash('admin_home_post', 'Votre post est en attente de validation, Vous serez informé lors de sa publication.');
            //TODO Penser à notifier l'admin lors de la publication d'un nouveau post
            return $this->redirectToRoute('post_list');
        }

        return $this->render('admin/post/edit.html.twig', [
            'edit_post_form' => $form->createView(),
            'post' => $post
        ]);
    }


    /**
     * @param Post $post
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('admin/post/enable/{post}', name: 'admin_post_enable')]
    public function enablePost(Post $post, EntityManagerInterface $manager): Response
    {
        $post->setActive(!$post->isActive());
//        @TODO Penser à notifier l'auteur du post une fois celui ci activé
        $manager->persist($post);
        $manager->flush();

        $response = $post->isActive() ? "L'article ({$post->getTitle()}) a été activé avec succès." : "L'article ({$post->getTitle()}) a été désactivé avec succès.";

        return new Response($response);
    }


    /**
     * @param Post $post
     * @param EntityManagerInterface $manager
     * @return RedirectResponse
     */
    #[Route('admin/post/remove/{post}', name: 'admin_post_remove')]
    public function removePost(Post $post, EntityManagerInterface $manager): RedirectResponse
    {
        $postTitle = $post->getTitle();
        $manager->remove($post);
        $manager->flush();
        $this->addFlash('admin_home_post', "Suppression de {$postTitle} terminée.");
        return $this->redirectToRoute('post_list');
    }

    #[Route('/search', name: 'search')]
    public function search(): Response
    {
        $query = new TypesenseQuery('molestia', 'content');

        // Get Doctrine Hydrated objects
        // $results = $this->postFinder->query($query)->getResults();

        // dump($results)
        // array:2 [▼
        //    0 => App\Entity\Book {#522 ▶}
        //    1 => App\Entity\Book {#525 ▶}
        //]

        // Get raw results from Typesence
        $rawResults = $this->postFinder->rawQuery($query)->getResults();

        return $this->render('search/index.html.twig', [
            'results' => $rawResults
        ]);
    }

}
