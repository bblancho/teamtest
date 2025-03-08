<?php

namespace App\Controller;

use App\Entity\Candidatures;
use App\Entity\Users;
use App\Form\UserType;
use App\Entity\Clients;
use App\Entity\Offres;
use App\Form\ClientType;
use App\Form\UserPasswordType;
use App\Repository\OffresRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CandidaturesRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Validator\Constraints\Expression;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use function Symfony\Component\Clock\now;

#[Route("/compte", 'user.')]
class UserController extends AbstractController
{
    /**
     * This controller allow us to edit user profile
     *
     * @param Clients $user
     * @param Request $request
     * @param EntityManagerInterface $manager
     * 
     * @return Response
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/edition-profil', name: 'edit', methods: ['GET', 'POST'] )]
    public function edit(
        Request $request,
        EntityManagerInterface $manager,
    ): Response {
        
        /** @var Clients $user */
        $user = $this->getUser() ;

        $form = $this->createForm(ClientType::class, $user) ;
        
        $form->handleRequest($request) ;

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Clients $user */
            $user = $form->getData() ;

            $user->setNom($form["nom"]->getData());
            $user->setAdresse($form["adresse"]->getData());
            $user->setCp($form["cp"]->getData());
            $user->setVille($form["ville"]->getData());
            $user->setPhone($form["phone"]->getData());
            $user->setTjm($form["tjm"]->getData());
            $user->setSiret($form["siret"]->getData());
            // $user->setDispo($form["dispo"]->getData());
            $user->setIsNewsletter($form["isNewsletter"]->getData());

            $manager->flush() ;

            $this->addFlash(
                'success',
                'Les informations de votre compte ont bien été modifiées.'
            );

            return $this->redirectToRoute('user.mesCandidatures');
        }

        return $this->render('pages/user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * This controller allow us to edit your password
     *
     * @param Users $user
     * @param Request $request
     * @param EntityManagerInterface $manager
     * 
     * @return Response
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/edition-mot-de-passe', 'password', methods: ['GET', 'POST'])]
    public function editPassword(
        Request $request,
        EntityManagerInterface $manager,
        UserPasswordHasherInterface $hasher
    ): Response {

        /** @var Clients $user */
        $user = $this->getUser() ;

        $form = $this->createForm(UserPasswordType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $newpass = $form->get("plainPassword")->getData();

            if ( $hasher->isPasswordValid( $user , $form->get('password')->getData()) ) {

                $hasher = $hasher->hashPassword(
                    $user,
                    $newpass
                );

                $user->setPassword($hasher);

                $this->addFlash(
                    'success',
                    'Le mot de passe a été modifié.'
                );

                $manager->flush();

                return $this->redirectToRoute('user.mesCandidatures');
            } 

            $this->addFlash(
                'warning',
                'Le mot de passe renseigné est incorrect.'
            );
        }

        return $this->render('pages/edit-password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * This controller allow us to edit user's profile
     *
     * @param Users $choosenUser
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[IsGranted(new Expression('is_granted("ROLE_USER") or is_granted("ROLE_ADMIN")'))]
    #[Route('/candidature/offre-{id}-{slug}', name: 'candidature', methods: ['GET'], requirements: ['id' => Requirement::DIGITS, 'slug' => Requirement::ASCII_SLUG])]
    public function candidature(
        OffresRepository $offresRepository, 
        CandidaturesRepository $candidaturesRepository, 
        int $id, 
        string $slug ,
        EntityManagerInterface $manager
    ): Response {
        
        if($this->isGranted('ROLE_SOCIETE'))
        {
            return $this->redirectToRoute('offre.mes_offres') ;
        }
        
        $mission    = $offresRepository->find($id);

        if( $mission->getSlug() != $slug ){
            return $this->redirectToRoute('offre.show', ['slug' => $mission->getSlug() , 'id' => $mission->getId()]) ;
        }

        /** @var Clients $freeLance */
        $freeLance  = $this->getUser() ;

        // On vérifie si le user a déjà postulé
        $candidature = $candidaturesRepository->aDejaPostule($freeLance, $mission);

        dd($candidature) ;

        if( $candidature != 0 ){
            $this->addFlash(
                'warning',
                'Vous avez déjà postulé à cette offre.'
            );
        }else{
            $freeLance = $this->getUser() ;

            $candidature = new Candidatures() ;
    
            $candidature->setOffres($mission)
                ->setClients($freeLance)
                ->setConsulte(false)
                ->setCreatedAt(new \DateTimeImmutable())
            ;

            $manager->persist($candidature);
            $manager->flush();

            $this->addFlash(
                'success',
                "Merci pour votre candidature, sans réponse de notre part sous un délai de 2 semaines,
                considérer votre candidature comme non retenue."
            );

        }

        return $this->redirectToRoute('user.mesCandidatures');
    }

    /**
     * This controller allow us to edit user's profile
     *
     * @return Response
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/mes-candidatures', name: 'mesCandidatures', methods: ['GET'])]
    public function mesCandidatures(
        CandidaturesRepository $candidatureRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {

        /** @var Clients $user */
        $user = $this->getUser();
        $idClient = $user->getId();

        //dd( "id = " . $idClient ) ;

        $candidatures =  $paginator->paginate(
            $candidatureRepository->findByUser($idClient),
            $request->query->getInt('page', 1),
            10
        );

        //$aposute = $candidatureRepository->userAsPostule($idClient);

        return $this->render('pages/user/mes-candidatures.html.twig', compact('candidatures'));
    }


}
