<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{

    #[Route('admin/category/list', name: 'category_list')]
    public function categoryList(CategoryRepository $categoryRepository): Response
    {   
        if($this->isGranted('ROLE_ADMIN') === false) {
            return $this->redirectToRoute('app_home');
        }

        $categories = $categoryRepository->findAll();
        $categoriesOrderId = $categoryRepository->findAllOrderId();

        return $this->render('category/list.html.twig', [
            'controller_name' => 'CategoryController',
            'categories' => $categories,
            'categoriesOrderId' => $categoriesOrderId
        ]);
    }

    #[Route('admin/category/new', name: 'new_category')]
    public function newCategory(Request $request, CategoryRepository $categoryRepository, EntityManagerInterface $entityManagerInterface): Response
    {
        if($this->isGranted('ROLE_ADMIN') === false) {
            return $this->redirectToRoute('app_home');
        }

        $newCategory = new Category();

        $form = $this->createForm(CategoryType::class, $newCategory);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            
            $entityManagerInterface->persist($newCategory);
            $entityManagerInterface->flush();

            unset($newCategory);
            unset($form);

            $newCategory = new Category();
            $form = $this->createForm(CategoryType::class, $newCategory);
            
            $this->addFlash('success', 'La catégorie a bien été créée.');
        }

        $categories = $categoryRepository->findAll();

        return $this->render('category/new.html.twig', [
            'controller_name' => 'CategoryController',
            'categories' => $categories,
            'form' => $form->createView()
        ]);
    }

    #[Route('/category/{id}', name: 'app_category')]
    public function index(Request $request, ProductRepository $productRepository, CategoryRepository $categoryRepository, int $id): Response
    {
        $session = $request->getSession();

        if($this->isGranted('ROLE_USER') === false && $this->isGranted('ROLE_ADMIN') === false) {
            if ($session->get('panier') === null){
                $session->set('panier', []);
            }
        }


        $productCategory = $productRepository->findByCategory($id);
        $category = $categoryRepository->findById($id)[0];
        $categories = $categoryRepository->findAll();

        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
            'category' => $category,
            'categories' => $categories,
            'products' => $productCategory
        ]);
    }

    #[Route('admin/category/ajaxEditCategory/{id}', name: 'app_category_ajaxEditCategory')]
    public function ajaxEditCategory(Request $request, 
                                     CategoryRepository $categoryRepository, 
                                     EntityManagerInterface $entityManagerInterface, 
                                     int $id): JsonResponse
    {
        if($this->isGranted('ROLE_ADMIN') === false) {
            return $this->redirectToRoute('app_home');
        }

        $success = true;
        $newNameCategory = $request->getContent();

        if($newNameCategory !== '') {
            $category = $categoryRepository->findOneById($id);

            $category->setName($newNameCategory);
            $entityManagerInterface->persist($category);
            $entityManagerInterface->flush();
        }
        else {
            $success = false;
        }


        return new JsonResponse(array('success' => $success));

    }

    #[Route('admin/category/ajaxRemoveCategory/{id}', name: 'app_category_ajaxRemoveCategory')]
    public function ajaxRemoveCategory(CategoryRepository $categoryRepository, 
                                      EntityManagerInterface $entityManagerInterface, 
                                      int $id): Response
    {
        if($this->isGranted('ROLE_ADMIN') === false) {
            return $this->redirectToRoute('app_home');
        }

        $category = $categoryRepository->findOneById($id);
        $entityManagerInterface->remove($category);
        $entityManagerInterface->flush();

        return new Response(
            'Suppression effectuée', 
             Response::HTTP_OK
        );
    }
}
