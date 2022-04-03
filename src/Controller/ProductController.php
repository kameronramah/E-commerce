<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(Request $request, ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        $session = $request->getSession();

        if($this->isGranted('ROLE_USER') === false || $this->isGranted('ROLE_ADMIN')) {
            if ($session->get('panier') === null){
                $session->set('panier', []);
            }
        }
        $allProducts = $productRepository->findAllAsc();
        $categories = $categoryRepository->findAll();

        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
            'products' => $allProducts,
            'categories' => $categories
        ]);
    }

    #[Route('/product/{id}', name: 'app_product_unitary')]
    public function showProduct(Request $request, ProductRepository $productRepository, CategoryRepository $categoryRepository, int $id): Response
    {
        $session = $request->getSession();

        if($this->isGranted('ROLE_USER') === false || $this->isGranted('ROLE_ADMIN')) {
            if ($session->get('panier') === null){
                $session->set('panier', []);
            }
        }
        
        $product = $productRepository->findOneById($id);
        $categories = $categoryRepository->findAll();

        return $this->render('product/unitary.html.twig', [
            'controller_name' => 'ProductController',
            'product' => $product,
            'categories' => $categories
        ]);
    }
}
