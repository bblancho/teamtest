<?php

namespace App\Controller;

use App\Entity\Candidatures;
use App\Entity\Clients;
use App\Entity\Users;
use App\Form\ClientType;
use App\Form\UserPasswordType;
use App\Repository\CandidaturesRepository;
use App\Repository\OffresRepository;
use App\Service\FileUploadService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

// use Symfony\Component\Validator\Constraints\Expression;

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
        FileUploadService $fileUploadService,
        ParameterBagInterface $parameterBag,
        UserService $userService,
    ): Response {
        
        /** @var Clients $user */
        $user = $this->getUser() ;

        $form = $this->createForm(ClientType::class, $user) ;
        
        $form->handleRequest($request) ;

        if (
            $form->isSubmitted()
            && $form->isValid()
        ) {

            /** @var Clients $user */
            $user = $form->getData() ;

            $formData = [
                'nom' => $form["nom"]->getData(),
                'adresse' => $form["adresse"]->getData(),
                'cp' => $form["cp"]->getData(),
                'ville' => $form["ville"]->getData(),
                'phone' => $form["phone"]->getData(),
                'tjm' => $form["tjm"]->getData(),
                'isNewsletter' => $form["isNewsletter"]->getData(),
            ];

            $cvFile = $form["cvFile"]->getData();

            $cvFileDirectory = $parameterBag->get('cv.upload_directory');
            $cvFileName = null;

            if (null !== $cvFile) {
                $cvFileName = $fileUploadService->renameUploadedFile($cvFile, $cvFileDirectory);
                // $user->setCvFile($cvFileName);
                // $user->setCvName($cvFile->getClientOriginalName());
            }

            $userService->updateUserFromForm($user, $formData, $cvFileName);

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
    #[Route('/{slug}-{id}/postuler', name: 'postuler', methods: ['GET'], requirements: ['id' => '\d+' , 'slug' => '[a-z0-9-]+'] )]
    public function postuler(
        OffresRepository $offresRepository, 
        CandidaturesRepository $candidaturesRepository, 
        int $id, 
        string $slug ,
        EntityManagerInterface $manager
    ): Response {

        $mission = $offresRepository->find($id);

        if($this->isGranted('ROLE_SOCIETE'))
        {
            return $this->redirectToRoute('offre.mes_offres') ;
        }

        if( $mission->getSlug() != $slug){
            return $this->redirectToRoute('offre.show', ['slug' => $mission->getSlug() , 'id' => $mission->getId()]) ;
        }

        $freeLance = $this->getUser() ;

        // On vérifie si le user a déjà postulé
        $candidature = $candidaturesRepository->aDejaPostule($freeLance, $mission);

        if( $candidature != null ){
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
                ->setSlug($mission->getSlug())
                ->setCreatedAt(new \DateTimeImmutable())
            ;

            $manager->persist($candidature);
            
            // je récupere l'ensemble des candidatures pour l'offre à laquelle on a postulé
            $nbCandidatures = $candidaturesRepository->nbCandidatures($mission) ;
            $nbCandidatures = $nbCandidatures + 1;

            // je rajoute la +1 au champs nb candidature de l'entité offre
            $mission->setNbCandidatures($nbCandidatures) ;
            
            $manager->persist($mission);
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

        $candidatures =  $paginator->paginate(
            $candidatureRepository->candidaturesUser( $user->getId() ),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('pages/user/mes-candidatures.html.twig', compact('candidatures'));
    }


}
