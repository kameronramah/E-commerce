<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category/{id}', name: 'app_category')]
    public function index(Request $request, ProductRepository $productRepository, CategoryRepository $categoryRepository, int $id): Response
    {
        $session = $request->getSession();

        if($this->isGranted('ROLE_USER') === false || $this->isGranted('ROLE_ADMIN')) {
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
}
