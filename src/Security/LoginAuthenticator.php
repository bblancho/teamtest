<?php

namespace App\Security;

use App\Entity\Users;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;

class LoginAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'security.login';

    public function __construct(private UrlGeneratorInterface $urlGenerator, private UserService $user)
    {
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->getPayload()->getString('email');

        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->getPayload()->getString('password')),
            [
                new CsrfTokenBadge('authenticate', $request->getPayload()->getString('_csrf_token')),            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

       /** @var Users $user */
        $user = $this->user->getUser();

        if(  in_array('ROLE_CLIENT', $user->getRoles(), true) ){ 
            // return new RedirectResponse($this->urlGenerator->generate('candidatures', ['id' => $user->getId()] ));
            return new RedirectResponse($this->urlGenerator->generate('app_index'));
        }

        if( in_array('ROLE_SOCIETE', $user->getRoles(), true)){
            // return new RedirectResponse($this->urlGenerator->generate('offres.mes_offres', ['id' => $user->getId()] ));
            return new RedirectResponse($this->urlGenerator->generate('app_index'));
        }
        
        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            // return new RedirectResponse($this->urlGenerator->generate('admin'));
            return new RedirectResponse($this->urlGenerator->generate('app_index'));
        }

        return new RedirectResponse($this->urlGenerator->generate('app_index'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
