<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/commande", name="app_order")
     */
    public function index(Cart $cart, Request $request)
    {
        if (!$this->getUser()->getAdresses()->getValues())
        {
            return $this->redirectToRoute('add_adress');
        }

        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);



        return $this->render('order/index.html.twig', [
            'form'=> $form->createView(),
            'cart'=> $cart->getFull()
        ]);
    }

    /**
     * @Route("/commande/recap", name="app_order_recap", methods={"POST"})
     */
    public function add(Cart $cart, Request $request)
    {


        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $date = new \DateTime();
            $carriers = $form->get('carriers')->getData();
            $delivry = $form->get('adresses')->getData();
            $delivry_content = $delivry->getFirstName().' '.$delivry->getLastName();
            $delivry_content .= '<br/>'.$delivry->getTelephone();
            if ($delivry->getCompany())
            {
                $delivry_content = '<br/>'.$delivry->getCompany();
            }
            $delivry_content .= '<br/>'.$delivry->getAdress();
            $delivry_content .= '<br/>'.$delivry->getCodepostal().' '.$delivry->getCommune();
            $delivry_content .= '<br/>'.$delivry->getPays();

            //Enregistrer ma commande Order()
            $order = new Order();
            $reference = $date->format('dmY').'-'.uniqid();
            $order->setReference($reference);
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setCarrierName($carriers->getName());
            $order->setCarrierPrice($carriers->getPrice());
            $order->setDelivery($delivry_content);
            $order->setState(0);

            $this->entityManager->persist($order);



        // Enregistrer mes produits OrderDetails()
            foreach ($cart->getFull() as $product)
            {

                $orderDetails = new OrderDetails();
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($product['product']->getName());
                $orderDetails->setQuantity($product['quantity']);
                $orderDetails->setPrice($product['product']->getPrice());
                $orderDetails->setTotal($product['product']->getPrice() * $product['quantity']);
                $this->entityManager->persist($orderDetails);



            }

          $this->entityManager->flush();





            return $this->render('order/add.html.twig', [
                'form'=> $form->createView(),
                'cart'=> $cart->getFull(),
                'carriers'=> $carriers,
                'delivery' => $delivry_content,
                'reference' =>$order->getReference()

            ]);

        }

        return $this->redirectToRoute('app_cart');

    }

}
