<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\DBAL\Connection;
use PHPUnit\Util\Json;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/product')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'app_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    #[Route('/cart', name: 'app_product_cart', methods: ['GET'])]
    public function userCart(ProductRepository $productRepository, TokenStorageInterface $tokenStorage, Connection $connection): Response
    {
        
        $user = $tokenStorage->getToken()->getUser();

        // Vérifier si l'utilisateur est authentifié
        if ($user instanceof \App\Entity\User) {
            // L'utilisateur est connecté
            // Vous pouvez maintenant accéder à ses informations
            $user = $user->getId();
            $sql = "SELECT * FROM `product` JOIN `product_user` USING(`product_id`) WHERE `user_id` = :userId";
            $parameters = [
                'userId' => $user,
            ];
            $result = $connection->executeQuery($sql, $parameters)->fetchAllAssociative();
            dd($result);
            return $this->render('product/cart.html.twig', [
                'products' => $result
            ]);
        } 
        else {
            return $this->redirectToRoute('app_login');
        }
    }

    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProductRepository $productRepository): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productRepository->save($product, true);

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/new.html.twig', [
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

    #[Route('/addToCart', name: 'app_product_cart', methods: ['POST'])]
    public function addCart(Request $request, ProductRepository $productRepository): JsonResponse
    {
        $jsonData = json_decode($request->getContent());
        $productId = $jsonData->productId;
        $product = $productRepository->find($productId);
        $product->addUser($this->getUser());
        $productRepository->save($product, true);
        return new JsonResponse(['status' => 'Product added to cart!'], Response::HTTP_CREATED);
    }
}
