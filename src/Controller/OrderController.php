<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\BasketRepository;
use App\Repository\CategoryRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\StatusRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class OrderController extends AbstractController
{

    #[Route('admin/order', name: 'app_order')]
    public function index(CategoryRepository $categoryRepository, OrderRepository $orderRepository, StatusRepository $statusRepository): Response
    {
        if($this->isGranted('ROLE_ADMIN') === false) {
            return $this->redirectToRoute('app_home');
        }

        $categories = $categoryRepository->findAll();
        $orders = $orderRepository->findAll();
        $status = $statusRepository->findAll();

        return $this->render('order/index.html.twig', [
            'controller_name' => 'OrderController',
            'categories' => $categories,
            'orders' => $orders,
            'allStatus' => $status
        ]);
    }

    #[Route('/order/create', name: 'app_orderCreate', methods: ['POST'])]
    public function orderCreate(Request $request, 
                                BasketRepository $basketRepository,
                                ProductRepository $productRepository, 
                                EntityManagerInterface $entityManager,
                                ValidatorInterface $validatorInterface,
                                StatusRepository $statusRepository)
    {
        if($this->isGranted('ROLE_USER') === false && $this->isGranted('ROLE_ADMIN') === false) {
            return new JsonResponse(array('connected' => false));
        }

        $orderCreate = true;
        $session = $request->getSession();
        $panier = $session->get('panier');
        $user = $this->getUser();
        $basketUser = $basketRepository->findOneByUtilisateur($user);
        
        if(count($panier) > 0)  {
            $basketUser->setContent($panier);
            $entityManager->persist($basketUser);
            $entityManager->flush();  
        }

        $basket = $basketUser->getContent();
        $entityManager->getConnection()->beginTransaction();
        try {
            $status = $statusRepository->findOneByName('En attente d\'expédition');
            
            $newOrder = new Order();
            $newOrder->setClient($user);
            $newOrder->setStreetDelivery($user->getStreet());
            $newOrder->setCityDelivery($user->getCity());
            $newOrder->setPostalCodeDelivery($user->getPostalCode());
            $newOrder->setContent($basket);
            $newOrder->setStatus($status);
            $newOrder->setCreatedAt(new DateTimeImmutable());

            foreach ($basket as $item) {
                $product = $productRepository->findOneById($item['id']);
                $product->setQuantityProduct($product->getQuantityProduct() - $item['quantity']);
                $errors = $validatorInterface->validate($product);
                if (count($errors) > 0) {
                    $errorsString = (string) $errors;
                    throw new Exception($errorsString);
                }
                $entityManager->persist($product);
            }

            $basketUser->setContent([]);

            $entityManager->persist($basketUser);
            $entityManager->persist($newOrder);
            $entityManager->flush(); 
            $entityManager->getConnection()->commit();
            $orderCreate = true;
        } catch(Exception $e) {
            $entityManager->getConnection()->rollBack();
            $orderCreate = false;
        }
                
        return new JsonResponse(array('orderCreate' => $orderCreate, 'panier' => $basket));
    }

    #[Route('admin/order/ajaxChangeStatus/{id}', name: 'app_order_ajaxChangeStatus')]
    public function ajaxChangeStatus(Request $request, 
                                     OrderRepository $orderRepository, 
                                     StatusRepository $statusRepository,
                                     EntityManagerInterface $entityManagerInterface, 
                                     int $id)
    {
        if($this->isGranted('ROLE_ADMIN') === false) {
            return $this->redirectToRoute('app_home');
        }

        $statusId = intval($request->getContent());
        $order = $orderRepository->findOneById($id);
        $status = $statusRepository->findOneById($statusId);

        $order->setStatus($status);
        $entityManagerInterface->persist($order);
        $entityManagerInterface->flush();

        return new Response(
            'Statut de la commande changée', 
             Response::HTTP_OK
        );
    }

}
