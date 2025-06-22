<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Entity\Societes;
use App\Entity\Users;
use App\Form\RegistrationClientFormType;
use App\Form\RegistrationSocieteFormType;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Random\RandomException;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

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
     * @throws RandomException
     * @throws TransportExceptionInterface
     */
    #[Route('/inscription-client', 'security.registration-client', methods: ['GET', 'POST'])]
    public function registerClient(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer
    ): Response
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

            $this->generateSecurityRegistration($user, $entityManager, $mailer);

            return $this->redirectToRoute('security.login');
        }

        return $this->render(
            'pages/register/registerClient.html.twig',
            [
                'registrationForm' => $form,
            ]
        );
    }

    /**
     * This controller allow us to register.
     */
    #[Route('/inscription-societe', 'security.registration.societe', methods: ['GET', 'POST'])]
    public function registerSociete(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
        UploaderHelper $helper,
        MailerInterface $mailer
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
            $this->generateSecurityRegistration($user, $entityManager, $mailer);

            return $this->redirectToRoute('security.login');
        }

        return $this->render(
            'pages/register/registerSociete.html.twig',
            [
                'registrationForm' => $form,
            ]
        );
    }

    /**
     * @param string $token
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[Route('/verify/email/{token}', name: 'app_verify_email')]
    public function verifyUserEmail(string $token, EntityManagerInterface $em): Response
    {
        $user = $em->getRepository(Users::class)->findOneBy(['confirmationToken' => $token]);

        if (!$user) {
            $this->addFlash(
                'danger',
                'Lien de confirmation invalide ou expiré.'
            );

            return $this->redirectToRoute('security.login');
        }

        $user->setIsVerified(true);
        $user->setConfirmationToken(null);
        $em->flush();

        $this->addFlash(
            'success',
            'Votre e-mail a été confirmé. Vous pouvez maintenant vous connecter.'
        );

        return $this->redirectToRoute('security.login');
    }

    /**
     * @param Clients|Societes $user
     * @param EntityManagerInterface $entityManager
     * @param MailerInterface $mailer
     * @return void
     * @throws RandomException
     * @throws TransportExceptionInterface
     */
    public function generateSecurityRegistration(
        Clients|Societes $user,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer
    ): void
    {
        $confirmationToken = bin2hex(random_bytes(32));
        $user->setConfirmationToken($confirmationToken);

        $entityManager->persist($user);
        $entityManager->flush();

        // On génère l’URL de confirmation
        $confirmationUrl = $this->generateUrl('app_verify_email', [
            'token' => $confirmationToken,
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        // Envoie l’e-mail
        $email = (new TemplatedEmail())
            ->from(new Address('noreply@team2i.com', 'Team2i'))
            ->to($user->getEmail())
            ->subject('Veuillez confirmer votre e-mail')
            ->htmlTemplate('emails/confirm_email.html.twig')
            ->context(
                [
                    'confirmationUrl' => $confirmationUrl,
                    'user' => $user,
                ]
            );

        $mailer->send($email);

        $this->addFlash(
            'success',
            'Votre compte a bien été créé. Un lien vous a été envoyé, veuillez confirmer votre e-mail.'
        );
    }


    
}
