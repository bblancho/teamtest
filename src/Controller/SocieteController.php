<?php

namespace App\Controller;

use App\Entity\Offres;
use App\Form\OffreType;
use App\Entity\Societes;
use App\Form\SocieteType;
use App\Form\UserPasswordType;
use App\Form\CreateSocieteFormType;
use App\Security\Voter\OffresVoter;
use App\Repository\OffresRepository;
use App\Repository\SocietesRepository;
use Doctrine\ORM\EntityManagerInterface;
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


#[Route("/societe", 'societe.')]
class SocieteController extends AbstractController
{

    /**
     * @param SocietesRepository $societesRepository
     * @param Request $request
     * 
     * @return Response
     */
    #[Route('/gestion', name: 'app_index')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(
        SocietesRepository $societesRepository,
        Request $request,
    ): Response {

        $page = $request->query->getInt('page', 1) ;
        $userId =  null ;

        $societes   = $societesRepository->paginateSocietes($page) ;

        return $this->render('template/admin/societes/index.html.twig', compact( "societes") );
    }

    /**
     * This controller allow us to register.
     */
    #[Route('/creation', 'create', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function createSociete(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
    ): Response
    {
        $societe = new Societes();
        
        $form = $this->createForm(CreateSocieteFormType::class, $societe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $hashedPassword = $passwordHasher->hashPassword(
                $societe,
                "Azerty24@"
            );

            $societe->setRoles(['ROLE_USER','ROLE_SOCIETE']);
            $societe->setTypeUser('societes');
            $societe->setPassword($hashedPassword);
            $societe->setIsVerified(true);

            $entityManager->persist($societe);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'La société a bien été créée.'
            );
            return $this->redirectToRoute('app_index');
        }

        return $this->render(
            'pages/societe/registerSociete.html.twig',
            [
                'registrationForm' => $form,
            ]
        );
    }

    /**
     * This method allow us to create an mission
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/creation-offre/{societe}', name: 'create.offre', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function createOffre(
        Request $request,
        Societes $societe,
        SocietesRepository $societesRepository,
        EntityManagerInterface $manager
    ): Response {

        $societe = $societesRepository->find($societe);
        $id      = $societe->getId();

        if (!$societe) {
            throw $this->createNotFoundException(
                'Aucune société trouvée pour cet id '.$id
            );
        }

        $mission = new Offres();

        $form = $this->createForm(OffreType::class, $mission);
        $form->handleRequest($request);

        if (  $form->isSubmitted() && $form->isValid()  ) {

            $mission->setSocietes( $societe ) ;
            $mission->setNbCandidatures(0) ;
            $mission->setSlug($form["nom"]->getData().$form["refMission"]->getData()) ;

            $manager->persist($mission);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre mission a été créé avec succès !'
            );

            return $this->redirectToRoute('app_index');
        }

        return $this->render('pages/missions/new.html.twig', [
            'form' => $form
        ]);
    }
    
    /**
     * This controller allow us to edit user's profile
     *
     * @param Societes $user
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/edition', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        EntityManagerInterface $manager,
        UploaderHelper $helper
    ): Response {

        /** @var Societes $user */
        $user = $this->getUser() ;

        $form = $this->createForm(SocieteType::class, $user);

        $form->handleRequest($request);

        $cheminFichier  = $helper->asset($user, 'imageFile') ;

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Societes $user */
            $user = $form->getData();
            $manager->flush();

            //dd($cheminFichier) ;

            // /** @var UploadFile $file */
            // $file = $form->get('imageFile')->getData() ;

            $this->addFlash(
                'success',
                'Les informations de votre compte ont bien été modifiées.'
            );

            //dd($user) ;

            return $this->redirectToRoute('offres.mes_offres', ['id' => $user->getId()]);
        }

        return $this->render('pages/societe/edit.html.twig', [
            'form' => $form,
            'user' => $user
        ]);
    }

    /**
     * This controller allow us to edit user's profile
     *
     * @param Societes $societe
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/edition-mot-de-passe', 'edit.password', methods: ['GET', 'POST'])]
    public function editPassword(
        Request $request,
        EntityManagerInterface $manager,
        UserPasswordHasherInterface $hasher
    ): Response {

        /** @var Societes $user */
        $societe = $this->getUser() ;

        $form = $this->createForm(UserPasswordType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $newpass = $form->get("plainPassword")->getData();

            if ($hasher->isPasswordValid($societe, $form->get('password')->getData())) {

                $hasher = $hasher->hashPassword(
                    $societe,
                    $newpass
                );

                $societe->setPassword($hasher);

                $manager->flush();

                $this->addFlash(
                    'success',
                    'Le mot de passe a été modifié.'
                );

                return $this->redirectToRoute('offres.mes_offres');

            } else {
                $this->addFlash(
                    'warning',
                    'Le mot de passe renseigné est incorrect.'
                );
            }
        }

        return $this->render('pages/edit-password.html.twig', [
            'form' => $form->createView()
        ]);
    }



}
