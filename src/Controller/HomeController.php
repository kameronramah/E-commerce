<?php

namespace App\Controller;

use App\Repository\BasketRepository;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, 
                          ProductRepository $productRepository, 
                          CategoryRepository $categoryRepository,
                          BasketRepository $basketRepository,
                          EntityManagerInterface $entityManager): Response
    {
        $session = $request->getSession();
        
        if($this->isGranted('ROLE_USER') === false && $this->isGranted('ROLE_ADMIN') === false) {
            if ($session->get('panier') === null){
                $session->set('panier', []);
            }
        }
        else {
            $panier = $session->get('panier');
            if(count($panier) > 0)  {
                $user = $this->getUser();
                $basketUser = $basketRepository->findOneByUtilisateur($user);
                $basketUser->setContent($panier);

                $entityManager->persist($basketUser);
                $entityManager->flush();
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
