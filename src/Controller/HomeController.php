<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        $session = $request->getSession();
        
        if($this->isGranted('ROLE_USER') === false || $this->isGranted('ROLE_ADMIN')) {
            if ($session->get('panier') === null){
                $session->set('panier', []);
            }
        }

        $lastProducts = $productRepository->lastProduct();
        $categories = $categoryRepository->findAll();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'lastProducts' => $lastProducts,
            'categories' => $categories
        ]);
    }
}
