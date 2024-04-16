<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
   
    #[Route('/{id}', name: 'detail')]
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
}
