<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BasketController extends AbstractController
{
    #[Route('/basket', name: 'app_basket')]
    public function index(ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        $lastProducts = $productRepository->lastProduct();
        $categories = $categoryRepository->findAll();

        return $this->render('basket/index.html.twig', [
            'controller_name' => 'BasketController',
            'lastProducts' => $lastProducts,
            'categories' => $categories
        ]);
    }
}
