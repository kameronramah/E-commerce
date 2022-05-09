<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(Request $request, ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        $session = $request->getSession();

        if($this->isGranted('ROLE_USER') === false && $this->isGranted('ROLE_ADMIN') === false) {
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

    #[Route('admin/product/new', name: 'app_product_new')]
    public function newProduct(Request $request, CategoryRepository $categoryRepository, EntityManagerInterface $entityManagerInterface): Response
    {

        if($this->isGranted('ROLE_ADMIN') === false) {
            return $this->redirectToRoute('app_home');
        }

        $newProduct = new Product();
        $form = $this->createForm(ProductType::class, $newProduct);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            
            $productImage = $form->get('image')->getData();

            if($productImage !== null) {

                $newFilename = uniqid().'.'.$productImage->guessExtension();
                $newProduct->setImage($newFilename);

                $productImage->move(
                    $this->getParameter('products_images'),
                    $newFilename
                );
                
            }

            $entityManagerInterface->persist($newProduct);
            $entityManagerInterface->flush();

            unset($newProduct);
            unset($form);
            $newProduct = new Product();
            $form = $this->createForm(ProductType::class, $newProduct);

            $this->addFlash('success', 'Le produit a bien été créé.');
        }

        $categories = $categoryRepository->findAll();

        return $this->render('product/new.html.twig', [
            'controller_name' => 'ProductController',
            'categories' => $categories,
            'form' => $form->createView()
        ]);
    }

    #[Route('admin/product/management', name: 'app_product_management')]
    public function productManagement(ProductRepository $productRepository, 
                                      CategoryRepository $categoryRepository): Response
    {
        if($this->isGranted('ROLE_ADMIN') === false) {
            return $this->redirectToRoute('app_home');
        }

        $products = $productRepository->findAllOrderId();
        $categories = $categoryRepository->findAll();

        return $this->render('product/management/management.html.twig', [
            'controller_name' => 'ProductController',
            'products' => $products,
            'categories' => $categories
        ]);
    }

    #[Route('admin/product/management/{id}', name: 'app_product_management_unitary')]
    public function productManagementUnitary(Request $request, 
                                             ProductRepository $productRepository, 
                                             CategoryRepository $categoryRepository,
                                             EntityManagerInterface $entityManagerInterface,
                                             int $id): Response
    {
        if($this->isGranted('ROLE_ADMIN') === false) {
            return $this->redirectToRoute('app_home');
        }

        $fileSystem = new Filesystem();
        $product = $productRepository->findOneById($id);
        $categories = $categoryRepository->findAll();
        $productImage = $product->getImage();

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $productNewImage = $form->get('image')->getData();

            if($productNewImage !== null) {

                $newFilename = uniqid().'.'.$productNewImage->guessExtension();
                $product->setImage($newFilename);

                $productNewImage->move(
                    $this->getParameter('products_images'),
                    $newFilename
                );

                $fileSystem->remove($this->getParameter('products_images') . '/' . $productImage);
                
            }
            else {
                $product->setImage($productImage);
            }

            $entityManagerInterface->persist($product);
            $entityManagerInterface->flush();

            $this->addFlash('success', 'Le produit a bien été modifié.');

        }

        return $this->render('product/management/unitaryManagement.html.twig', [
            'controller_name' => 'ProductController',
            'product' => $product,
            'categories' => $categories,
            'form' => $form->createView()
        ]);
    }

    #[Route('admin/product/management/{id}/remove', name: 'app_product_management_remove')]
    public function productManagementRemove(ProductRepository $productRepository,
                                             EntityManagerInterface $entityManagerInterface,
                                             int $id): Response
    {
        if($this->isGranted('ROLE_ADMIN') === false) {
            return $this->redirectToRoute('app_home');
        }

        $fileSystem = new Filesystem();

        $product = $productRepository->findOneById($id);

        $entityManagerInterface->remove($product);
        $entityManagerInterface->flush();
        $fileSystem->remove($this->getParameter('products_images') . '/' . $product->getImage());

        return new Response(
            'Suppression effectuée', 
             Response::HTTP_OK
        );
    }

    #[Route('/product/{id}', name: 'app_product_unitary')]
    public function showProduct(ProductRepository $productRepository, CategoryRepository $categoryRepository, int $id): Response
    {    
        $product = $productRepository->findOneById($id);
        $categories = $categoryRepository->findAll();

        return $this->render('product/unitary.html.twig', [
            'controller_name' => 'ProductController',
            'product' => $product,
            'categories' => $categories
        ]);
    }

}
