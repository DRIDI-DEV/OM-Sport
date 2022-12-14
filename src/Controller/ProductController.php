<?php

namespace App\Controller;

use App\Classe\Search;
use App\Entity\Product;
use App\Form\SearchType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private $entityManager ;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/nos-produits", name="app_products")
     */
    public function index(request $request )
    {


        $products = $this->entityManager->getRepository(Product::class)->findAll();

        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            //$search =$form->getData();
            $products = $this->entityManager->getRepository(Product::class)->FindWithSearch($search);

        }


        return $this->render('product/index.html.twig',
            ['products' => $products,
                'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/produit/{slug}", name="app_product")
     */
    public function show($slug)
    {

        $product = $this->entityManager->getRepository(Product::class)->findOneBySlug($slug);
        $product_best = $this->entityManager->getRepository(Product::class)->findByIsBest(1) ;

        return $this->render('product/show.html.twig',
            ['product' => $product,
             'product_best' => $product_best
            ]);
    }
}
