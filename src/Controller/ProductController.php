<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/product', name: 'app_product_')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'list')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
        
        return $this->render('product/list.html.twig', ['products' => $products]);
    }
   
    #[Route('/{id}', name: 'detail',requirements: ['id' => '\d+'])]
    public function detail($id,ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);
        
        return $this->render('product/detail.html.twig', ['product' => $product]);
    }
    #[Route('/delete/{id}', name: 'delete')]
    public function delete($id,ProductRepository $productRepository,EntityManagerInterface $em): Response
    {
        $product = $productRepository->find($id);
        $em->remove($product);
        $em->flush();
        $this->addFlash(
            'notice',
            'Produit supprime avec succes!'
        );
        
        //return $this->render('product/delete.html.twig', []);
        return $this->redirectToRoute('app_product_list');
    }
    #[Route('/{min}/{max}', name: 'listbyprice')]
    public function listByPrice($min,$max,ProductRepository $productRepository): Response
    {
        $products = $productRepository->findByPriceDQL($min,$max);
        
        return $this->render('product/listByPrice.html.twig', ['products' => $products]);
    }
    
    #[Route('/add', name: 'add')]
    public function add(Request $request,ProductRepository $productRepository,EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        
        $produit = new Product();
        $form = $this->createForm(ProductType::class,$produit);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $produit = $form->getData();
            
            $em->persist($produit);
            $em->flush();
            $this->addFlash(
                'notice',
                'Produit Ajoute avec succes!'
            );

            // ... perform some action, such as saving the task to the database

            return $this->redirectToRoute('app_product_list');
        }

        return $this->render('product/add.html.twig', ['form' => $form]);
    }
    #[Route('/test', name: 'test')]
    public function test(): JsonResponse
    {
        //$products = $productRepository->findByPriceDQL($min,$max);
        $tab = ["nom"=>"test1","nom"=>'test2'];
        return $this->json($tab);
    }
}
