<?php

namespace App\Controller;

use App\Repository\BasketRepository;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Util\Json;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BasketController extends AbstractController
{
    #[Route('/basket', name: 'app_basket')]
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
            $panier = $session->get('panier');
        }
        else {
            $user = $this->getUser();
            $panierUser = $basketRepository->findOneByUtilisateur($user);
            $panier = $panierUser->getContent();
        }

        for($i = 0; $i < count($panier); $i++) {
            $product = $productRepository->findById($panier[$i]['id'])[0];
            $panier[$i]['maxQuantity'] = $product->getQuantityProduct();
            if($panier[$i]['quantity'] > $product->getQuantityProduct() && $product->getQuantityProduct() !== 0) {
                $panier[$i]['quantity'] = $product->getQuantityProduct();
            }
            else if($product->getQuantityProduct() == 0) {
                unset($panier[$i]);
                $panier = array_values($panier);
            }
        }

        if($this->isGranted('ROLE_USER') === false || $this->isGranted('ROLE_ADMIN') === false) {
            $session->set('panier', $panier);
        }
        else {
            $panierUser->setContent($panier);
            $entityManager->persist($panierUser);
            $entityManager->flush();
        }

        
        $categories = $categoryRepository->findAll();

        return $this->render('basket/index.html.twig', [
            'controller_name' => 'BasketController',
            'panier' => $panier,
            'categories' => $categories
        ]);
    }

    #[Route('/basket/add',  name: 'ajaxAdd_basket', methods: ['POST'] )]
    public function ajaxAdd(Request $request, 
                            ProductRepository $productRepository,
                            BasketRepository $basketRepository,
                            EntityManagerInterface $entityManager) {
        
        $session = $request->getSession();
        $idProduct = $request->get('id');
        $size = $request->get('size');
        $itemExist = false;
        $item = $productRepository->findById($idProduct);
        $itemInsert = true;
        $totalQuantitySameItem = 0;
        
        if($this->isGranted('ROLE_USER') === false && $this->isGranted('ROLE_ADMIN') === false) {
            /* Ajout du panier dans la session */
            if ($session->get('panier') === null){
                $session->set('panier', []);
            }
            $panier = $session->get('panier');
        }
        else {
            $user = $this->getUser();
            $panierUser = $basketRepository->findOneByUtilisateur($user);
            $panier = $panierUser->getContent();
        }

        if($item[0]->getQuantityProduct() > 0) {

            /* Comptage de la quantité présente dans le panier pour cet item */
            foreach ($panier as $itemBasket) {
                if($itemBasket['id'] == $idProduct) {
                    $totalQuantitySameItem += $itemBasket['quantity'];
                }
            }

            /* Vérification si le produit est déjà présent dans le panier */
            for ($i = 0; $i < count($panier); $i++) { 
                if($panier[$i]['id'] == $idProduct && $panier[$i]['size'] == $size) {
                    if($totalQuantitySameItem + 1 > $item[0]->getQuantityProduct()) {
                        $itemInsert = false;
                    }
                    else {
                        $panier[$i]['quantity'] += 1;
                        $panier[$i]['price'] = round($panier[$i]['price'] + $item[0]->getPrice(), 2);
                    }
                    $itemExist = true;
                }
            }
            
            if($itemExist === false && $totalQuantitySameItem + 1 <= $item[0]->getQuantityProduct()) {
                $newItem['id'] = $idProduct;
                $newItem['name'] = $item[0]->getName();
                $newItem['image'] = $item[0]->getImage();
                $newItem['price'] = round($item[0]->getPrice(), 2);
                $newItem['quantity'] = 1;
                $newItem['size'] = $size;
                array_push($panier, $newItem);
            }
        }
        else {
            $itemInsert = false;
        }

        if($this->isGranted('ROLE_USER') === false && $this->isGranted('ROLE_ADMIN') === false) {
            $session->set('panier', $panier);
        }
        else {
            $panierUser->setContent($panier);
            $entityManager->persist($panierUser);
            $entityManager->flush();
        }

        return new JsonResponse(array('itemInsert' => $itemInsert));
    }


    #[Route('/basket/modify/{id}/{size}/{change}',  name: 'ajaxModifyQuantity_basket', methods: ['PUT'] )]
    public function ajaxModify(Request $request, 
                               ProductRepository $productRepository, 
                               BasketRepository $basketRepository,
                               EntityManagerInterface $entityManager,
                               int $id, 
                               int $size, 
                               int $change) {
        $session = $request->getSession();
        $totalQuantitySameItem = 0;
        $product = $productRepository->findById($id)[0];
        $removeElements = [];

        if($this->isGranted('ROLE_USER') === false && $this->isGranted('ROLE_ADMIN') === false) {
            $panier = $session->get('panier');
        }
        else {
            $user = $this->getUser();
            $panierUser = $basketRepository->findOneByUtilisateur($user);
            $panier = $panierUser->getContent();
        }

        /* Comptage de la quantité présente dans le panier pour cet item */
        foreach ($panier as $itemBasket) {
            if($itemBasket['id'] == $id) {
                $totalQuantitySameItem += $itemBasket['quantity'];
            }
        }

        foreach ($panier as $key => $itemBasket) {
            if($itemBasket['id'] == $id && $itemBasket['size'] == $size) {
                if(($itemBasket['quantity'] + $change) == 0) {
                    array_push($removeElements, $panier[$key]);
                    unset($panier[$key]);
                    $panier = array_values($panier);
                }
                else {
                    if(($totalQuantitySameItem + $change) <= $itemBasket['maxQuantity']) {
                        $panier[$key]['quantity'] += $change;
                        if($change == 1) {
                            $panier[$key]['price'] = round($panier[$key]['price'] + $product->getPrice(), 2);
                        }
                        else {
                            $panier[$key]['price'] = round($panier[$key]['price'] - $product->getPrice(), 2);
                        }
                    }
                }
            }
        }

        if($this->isGranted('ROLE_USER') === false && $this->isGranted('ROLE_ADMIN') === false) {
            $session->set('panier', $panier);
        }
        else {
            $panierUser->setContent($panier);
            $entityManager->persist($panierUser);
            $entityManager->flush();
        }

        return new JsonResponse(array('panier' => $panier, 'removeElements' => $removeElements));
    }


    #[Route('/basket/remove/{id}/{size}',  name: 'ajaxRemove_basket', methods: ['DELETE'] )]
    public function ajaxRemove(Request $request,
                               BasketRepository $basketRepository,
                               EntityManagerInterface $entityManager, 
                               int $id, 
                               int $size) {
        $session = $request->getSession();
        $removeElements = [];

        if($this->isGranted('ROLE_USER') === false && $this->isGranted('ROLE_ADMIN') === false) {
            $panier = $session->get('panier');
        }
        else {
            $user = $this->getUser();
            $panierUser = $basketRepository->findOneByUtilisateur($user);
            $panier = $panierUser->getContent();
        }

        foreach ($panier as $key => $itemBasket) {
            if($itemBasket['id'] == $id && $itemBasket['size'] == $size) {
                array_push($removeElements, $panier[$key]);
                unset($panier[$key]);
                $panier = array_values($panier);
            }
        }

        if($this->isGranted('ROLE_USER') === false && $this->isGranted('ROLE_ADMIN') === false) {
            $session->set('panier', $panier);
        }
        else {
            $panierUser->setContent($panier);
            $entityManager->persist($panierUser);
            $entityManager->flush();
        }

        return new JsonResponse(array('panier' => $panier, 'removeElements' => $removeElements));
    }
}
