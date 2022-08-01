<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AcountController extends AbstractController
{
    /**
     * @Route("/compte", name="acount")
     */
    public function index(): Response
    {
        return $this->render('acount/index.html.twig', [
            'controller_name' => 'AcountController',
        ]);
    }
}
