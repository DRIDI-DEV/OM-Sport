<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountPasswordController extends AbstractController
{

    private $entityManager;

    /**
     * AccountPasswordController constructor.
     * @param $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/compte/Modifier-mon-mot-de-passe", name="app_account_password")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder)
    {

        $notif = null;
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $old_password = $form->get('ancienne_password')->getData();

            if ($encoder->isPasswordValid($user, $old_password))
            {
                $new_password = $form->get('Nouveau_password')->getData();
                $password = $encoder->encodePassword($user, $new_password);

                $user ->setPassword($password);
                $this->entityManager->persist($user);  // en cas de mise à jour en peut enlevé la méthode persist()
                $this->entityManager ->flush();
                $notif = "votre mot de pass à éte bien mis à jour";

            }
            else
            {
                $notif = "votre mot de passe actuel n est pas bon";
            }

        }





        return $this->render('acount/password.html.twig', [
            'form' => $form->createView(),
            'notification' => $notif
        ]);
    }
}
