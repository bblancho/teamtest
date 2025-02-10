<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Clients;
use App\Entity\Societes;
use App\Service\UserService;
use App\Form\RegistrationFormType;
use App\Form\RegistrationClientFormType;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\RegistrationSocieteFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    
    public function __construct(private UserService $user)
    {
    }

    #[Route(path: '/connexion', name: 'security.login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->user->getUser()) {
            return $this->redirectToRoute('app_index');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('pages/security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/deconnexion', name: 'security.logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * This controller allow us to register.
     */
    #[Route('/inscription', 'security.registration', methods: ['GET'])]
    public function register(): Response
    {
        return $this->render('pages/register/register.html.twig');
    }

    /**
     * This controller allow us to register.
     */
    #[Route('/inscription-client', 'security.registration-client', methods: ['GET', 'POST'])]
    public function registerClient(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new Clients();
        $user->setRoles(['ROLE_USER','ROLE_CLIENT']);
        $user->setTypeUser('clients');
        
        $form = $this->createForm(RegistrationClientFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $form->get('password')->getData()
            );

            $user->setPassword($hashedPassword);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Votre compte a bien été créé.'
            );

            return $this->redirectToRoute('security.login');
        }

        return $this->render('pages/register/registerClient.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    /**
     * This controller allow us to register.
     */
    #[Route('/inscription-societe', 'security.registration.societe', methods: ['GET', 'POST'])]
    public function registerSociete(Request $request, UserPasswordHasherInterface $passwordHasher, 
        EntityManagerInterface $entityManager,
        UploaderHelper $helper
    ): Response
    {
        $user = new Societes();
        $user->setRoles(['ROLE_USER','ROLE_SOCIETE']);
        $user->setTypeUser('societes');
        
        $form = $this->createForm(RegistrationSocieteFormType::class, $user);
        $form->handleRequest($request);
        $cheminFichier  = $helper->asset($user, 'imageFile') ;

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $form["password"]->getData()
            );

            $user->setPassword($hashedPassword);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Votre compte a bien été créé.'
            );

            return $this->redirectToRoute('security.login');
        }

        return $this->render('pages/register/registerSociete.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
