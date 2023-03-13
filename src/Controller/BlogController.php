<?php

namespace App\Controller;

use ACSEO\TypesenseBundle\Finder\TypesenseQuery;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
//    private $postFinder;
//
//    public function __construct($postFinder)
//    {
//        $this->postFinder = $postFinder;
//    }

    #[Route('/blog', name: 'blog_home')]
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('blog/index.html.twig', [
            'posts' => $postRepository->getActivePostsForPublicBlog(),
        ]);
    }
//
//    #[Route('/search', name: 'search')]
//    public function search()
//    {
//        $query = new TypesenseQuery('Jules Vernes', 'author');
//
//        // Get Doctrine Hydrated objects
//        $results = $this->postFinder->query($query)->getResults();
//
//        // dump($results)
//        // array:2 [▼
//        //    0 => App\Entity\Book {#522 ▶}
//        //    1 => App\Entity\Book {#525 ▶}
//        //]
//
//        // Get raw results from Typesence
//        $rawResults = $this->postFinder->rawQuery($query)->getResults();
//
//    }
}
