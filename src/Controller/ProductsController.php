<?php

namespace App\Controller;

use App\Entity\CartLine;
use App\Entity\Product;
use App\Form\CartLineType;
use App\Form\ProductType;
use App\Repository\CartLineRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    public function __construct(ProductRepository $productRepository, CartLineRepository $cartLineRepository)
    {
        $this->productRepository = $productRepository;
        $this->cartLineRepository = $cartLineRepository;
    }

    /**
     * @Route("/", name="test")
     */
    public function test()
    {
        return $this->render('products/test.html.twig');
    }

    /**
     * @Route("/test", name="products")
     */
    public function index(Request $request, EntityManagerInterface $entityManager)
    {
        $products = $this->productRepository->findAll();

        $newProduct = new Product();
        $form = $this->createForm(ProductType::class, $newProduct);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $newProduct = $form->getData();
            $image = $form->get('image')->getData();
            $imageName = md5(uniqid()).'.'.$image->guessExtension();
            $image->move($this->getParameter('upload_files'), $imageName);
            $newProduct->setImage($imageName);

            $entityManager->persist($newProduct);
            $entityManager->flush();

            $this->addFlash('success', 'Votre produit a bien été créé !');
            return $this->redirectToRoute('products');
        }

        return $this->render('products/index.html.twig', [
            'onPage' => 'products',
            'productForm' => $form->createView(),
            'products' => $products
        ]);
    }

    /**
     * @Route("/product/{id}", name="product")
     */
    public function singleProduct($id, Request $request, EntityManagerInterface $entityManager)
    {
        $product = $this->productRepository->find($id);
        
        $newCartLine = new CartLine();
        $form = $this->createForm(CartLineType::class, $newCartLine);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $newCartLine = $form->getData();
            $newCartLine->setProduct($product);

            $entityManager->persist($newCartLine);
            $entityManager->flush();

            $this->addFlash('success', 'Le produit a bien été ajouté à votre panier !');
            return $this->redirectToRoute('shopping_cart');
        }

        return $this->render('products/product.html.twig', [
            'onPage' => '',
            'product' => $product,
            'cartLineForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/product/remove/{id}", name="removeProduct")
     */
    public function removeProduct($id, EntityManagerInterface $entityManager)
    {
        $product = $this->productRepository->find($id);

        $product->deleteFile();

        $entityManager->remove($product);
        $entityManager->flush();

        $this->addFlash('success', 'Le produit a bien été supprimé !');
        return $this->redirectToRoute('products');
    }

     /**
     * @Route("/panier", name="shopping_cart")
     */
    public function ShoppingCart()
    {
        $cartLines = $this->cartLineRepository->findAll();

        $totalQuantity = 0;
        $totalPrice = 0;
        foreach($cartLines as $cartLine) {
            $totalQuantity = $totalQuantity + $cartLine->getQuantity();

            $price = $cartLine->getProduct()->getPrice();
            $quantity = $cartLine->getQuantity();
            $totalPrice = $totalPrice + ($price * $quantity);
        }

        return $this->render('products/shopping_cart.html.twig', [
            'onPage' => 'shopping_cart',
            'cartLines' => $cartLines,
            'totalPrice' => $totalPrice,
            'totalQuantity' => $totalQuantity
        ]);
    }

    /**
     * @Route("/panier/remove/{id}", name="removeCartLine")
     */
    public function removeCartLine($id, EntityManagerInterface $entityManager)
    {
        $cartLine = $this->cartLineRepository->find($id);

        $entityManager->remove($cartLine);
        $entityManager->flush();

        $this->addFlash('success', 'Le produit a bien été retiré de votre panier !');
        return $this->redirectToRoute('shopping_cart');
    }
}
