<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/inscription", name="app_register")
     */
    public function index(request $request, UserPasswordEncoderInterface $encoder): Response
    {

        $notification = null;
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $user = $form->getData();
            $search_mail = $this->entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());

            if (!$search_mail){

            $password = $encoder->encodePassword($user, $user->getPassword());

            $user->setPassword($password);

            $this->entityManager->persist($user);

            $this->entityManager->flush();

            $mail = new Mail();
            $subject = " Bienvenue sur la boutique tunisienne";
            $content = "Bonjour " .$user->getFullName().", vous pouvez connecté sur votre compte ";

            $mail->send($user->getEmail(), $user->getFirstname(), $subject, $content);

            $notification = "Vous pouvez connecté à votre compte";
            }
            else{
                $notification = "cet email est existe déja";
            }

        }


        return $this->render('register/index.html.twig', [
            'form' =>$form->createView(),
            'notification' => $notification]);
    }
}
