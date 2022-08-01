<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetPasswordController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/mot-de-passe-oublie", name="app_reset_password")
     */
    public function index(Request $request)
    {
        if ($this->getUser())
        {
            return $this->redirectToRoute('app_home');
        }

        if ($request->get('email'))
        {
           $user =  $this->entityManager->getRepository(User::class)->findOneByEmail($request->get('email'));

           // Enrgistrer la demande reset passwword avec user, tocken et createdAt
            if ($user)
            {
                $reset_password = new ResetPassword();
                $reset_password->setUser($user);
                $reset_password->setToken(uniqid());
                $reset_password->setCreatedAt(new \DateTimeImmutable());
                $this->entityManager->persist($reset_password);
                $this->entityManager->flush();

            // Envoyer un email à l'utilisateur avec un lien lui permettant de mettre à jour son mot de passe.
                $url = $this->generateUrl('app_update_password', [
                    'token' => $reset_password->getToken()
                ]);
                $mail = new Mail();
                $subject = " Réinitialiser votre mot de passe sur la boutique tunisienne";
                $content = "Bonjour " .$user->getFirstName()."<br/vous avez demandé à réinitialisé votre mot de passe sur le site La Boutique Française.<br/><br/> ";
                $content .="Merci de bien vouloir cliquer sur le lien suivant pour <a href='".$url."'>mettre à jour votre mot de passe</a>";

                $mail->send($user->getEmail(), $user->getFirstname(), $subject, $content);
                $this->addFlash('notice', 'Vous allez recevoir un mail avec la procédure pour réinitialiser votre mot de passe.');
            }
            else{
                $this->addFlash('notice', 'Cette adresse email est inconnue.');

            }


        }

        return $this->render('reset_password/index.html.twig');
    }

    /**
     * @Route("/modifier-mot-de-passe/{token}", name="app_update_password")
     */
    public function update(Request $request, $token, UserPasswordEncoderInterface $encoder)
    {

        $reset_password = $this->entityManager->getRepository(ResetPassword::class)->findOneByToken($token);
        if (!$reset_password)
        {
            return $this->redirectToRoute('app_reset_password');
        }

        // vérifier si createdAt et -1 heure

        $now = new \DateTime();

        if ($now > $reset_password->getCreatedAt()->modify('+ 1 hour'))
        {
            $this->addFlash('notice', 'Votre demande de mot de passe a expiré. Merci de la renouveller.');
            return $this->redirectToRoute('app_reset_password');
        }

        //Rendre une vue avec mot de passe et confirmez votre mot de passe.

        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $new_pwd = $form->get('Nouveau_password')->getData();

            //Encodage des mots de passe.
            $new_password = $encoder->encodePassword($reset_password->getUser(), $new_pwd);
            $reset_password->getUser()->setPassword($new_password);

            //Flush en base de données.
            $this->entityManager->flush();

            //Redirection de l'utilisateur vers la page de connexion
            $this->addFlash('notice', 'Votre mot de passe a eté bien mis à jour.');
            return $this->redirectToRoute('app_login');

        }

        return $this->render('reset_password/update.html.twig', [
            'form' => $form->createView()
        ]);






    }
}
