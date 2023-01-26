<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('admin/category', name: 'category_list')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('admin/category/index.html.twig', [
            'categories' => $categoryRepository->getSomeCategories(10),
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('admin/category/add', name: 'category_add')]
    public function add(Request $request, EntityManagerInterface $manager): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setAuthor($this->getUser());
            $manager->persist($category);
            $manager->flush();
            $this->addFlash('category_message', "Catégorie {$form->getData()->getTitle()} ajoutée avec succès.");
            return $this->redirectToRoute('category_list');
        }

        return $this->render('admin/category/add.html.twig', [
            'category_form' => $form->createView()
        ]);
    }


    /**
     * @param Category $category
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('admin/category/remove/{category}', name: 'category_delete')]
    public function remove(Category $category, EntityManagerInterface $entityManager): Response {
        $title = $category->getTitle();
        $entityManager->remove($category);
        $entityManager->flush();
        $this->addFlash('category_message', "La catégorie <b> {$title} </b> a eté supprimée avec succès.");
        return $this->redirectToRoute('category_list');
    }
}
