<?php

namespace App\Service;

use App\Entity\Clients;
use App\Entity\Societes;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SendEmailService
{

    public function __construct(
        private Security $security,
        private EntityManagerInterface $entityManager,
        private MailerInterface $mailer,
        private UserService $userService
    ){}

    /**
     * @param string $from
     * @param Clients|Societes $user,
     * @param string $sujet
     * @param string $template
     * @param array  $context
     * 
     * @return void
     */
    public function send(string $from, Clients|Societes $user , string $sujet, string $template, array $context): void
    {   
        $confirmationToken = bin2hex(random_bytes(32));
        $user->setConfirmationToken($confirmationToken);

        // On génère l’URL de confirmation
        $confirmationUrl = $this->generateUrl('app_verify_email', [
            'token' => $confirmationToken,
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        // on crée l’e-mail
        $email = (new TemplatedEmail())
            ->from($from)
            ->to( $user->getEmail() )
            ->subject($sujet)
            ->htmlTemplate("emails/$template.html.twig")
            ->context($context)
        ;

        // Envoie l’e-mail
        $this->mailer->send($email);
    }

    
}
