<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ProductUser;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\ProductUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/product')]
class ProductController extends AbstractController
{
    //route which will be used to display all products
    #[Route('/', name: 'app_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    //route which shows the cart of the user
    #[Route('/cart', name: 'app_product_cart', methods: ['GET','POST'])]
    public function userCart(TokenStorageInterface $tokenStorage, Connection $connection): Response
    {
        if($this->getUser() == null){
            return $this->redirectToRoute('app_login');
        }
        $user = $tokenStorage->getToken()->getUser();

        // Check if user is logged in
        if ($user instanceof \App\Entity\User) {
            
            // user is logged in
            $user = $user->getId();
            $sql = "SELECT * FROM `product` JOIN `product_user` USING(`product_id`) WHERE `user_id` = :userId";
            $parameters = [
                'userId' => $user,
            ];

            // finding all products in the cart of the user in the database
            $result = $connection->executeQuery($sql, $parameters)->fetchAllAssociative();
            return $this->render('product/cart.html.twig', [
                'products' => $result
            ]);
        } 
    }

    // route which will be used to add a product to the cart by ajax
    #[Route('/addToCart', name: 'app_product_add_cart', methods: ['POST'])]
    public function addCart(Request $request, ProductUserRepository $productUserRepository, ProductRepository $productRepository): JsonResponse
    {
        $jsonData = json_decode($request->getContent());
        $productId = $jsonData->productId;
        $product = $productRepository->find($productId);
        $productUser = new ProductUser();

        // find the current user logged in
        $productUser->setUser($this->getUser());
        $productUser->setProduct($product);
        $productUserRepository->save($productUser, true);
        return new JsonResponse(['status' => 'Product added to cart!'], Response::HTTP_CREATED);
    }

   // route which will be used to add a product to the cart by ajax
   #[Route('/filters', name: 'app_product_filters', methods: ['POST'])]
   public function filters(Request $request, ProductRepository $productRepository): JsonResponse
   {
        $jsonData = json_decode($request->getContent());
        $filter = $jsonData->filter;
        $product = $productRepository->find($filter);
        return new JsonResponse($product);
   }

    // route which will be used to create a new product
    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProductRepository $productRepository): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        // checking if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            $productRepository->save($product, true);

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productRepository->save($product, true);

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $productRepository->remove($product, true);
        }

        return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
    }
}
