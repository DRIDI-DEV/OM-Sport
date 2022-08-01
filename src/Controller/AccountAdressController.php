<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Adress;
use App\Form\AdressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use function Doctrine\Common\Cache\Psr6\get;

class AccountAdressController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager ;
    }

    /**
     * @Route("/compte/adresses", name="app_account_adress")
     */
    public function index()
    {

        return $this->render('acount/adress.html.twig');
    }

    /**
     * @Route("/Ajouter_adresse", name="add_adress")
     */
    public function add(Cart $cart, Request $request)
    {
        $adress = new Adress();

        $form = $this->createForm(AdressType::class, $adress);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $adress->setUser($this->getUser());
            $this->entityManager->persist($adress);
            $this->entityManager->flush();

            if ($cart->get())
            {
                return $this->redirectToRoute('app_order');
            }

            return $this->redirectToRoute('app_account_adress');


        }

        return $this->render('acount/adress_form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/Modifier_adresse/{id}", name="edit_adress")
     */

    public function edit(Request $request, $id)
    {
        $adress = $this->entityManager->getRepository(Adress::class)->findOneById($id);

        if (!$adress || $adress->getUser() != $this->getUser())
        {
            $this->redirectToRoute('app_account_adress');
        }


        $form = $this->createForm(AdressType::class, $adress);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {

            $this->entityManager->flush();
            return $this->redirectToRoute('app_account_adress');


        }

        return $this->render('acount/adress_form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/Supprimer_adresse/{id}", name="remove_adress")
     */

    public function remove($id)
    {
        $adress = $this->entityManager->getRepository(Adress::class)->findOneById($id);

        if ($adress && $adress->getUser() == $this->getUser())
        {

        $this->entityManager->remove($adress);
            $this->entityManager->flush();
        }
             return $this->redirectToRoute('app_account_adress');
        }


}
