<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Classe\Mail;
use App\Entity\Carrier;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderSuccessController extends AbstractController
{

    private $entityManager;


    public function __construct(EntityManagerInterface $entityManager)
    {

        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/commande/merci/{stripeSessionId}", name="app_order_validate")
     */
    public function index(Cart $cart, $stripeSessionId): Response
    {

        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);

        if (!$order || $order->getUser() != $this->getUser())
        {
            return $this->redirectToRoute('app_home');
        }

        if ($order->getState() ==0)
        {
        // vider la session (panier)
        $cart->remove();
        // Modifier le statut isPaid
        $order->setState(1);
        $this->entityManager->flush();

        // envoi de mail de paiement
        $mail = new Mail();
        $subject = " Votre commande sur la boutique tunisienne est bien validée";
        $content = "Bonjour " .$order->getUser()->getFullName().".<br/>Merci pour votre paiement ";
        $mail->send($order->getUser()->getEmail(), $order->getuser()->getFirstname(), $subject, $content);

        }


        return $this->render('order_success/index.html.twig', [
            'order' => $order,
        ]);
    }
}
