<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Skills;
use App\Entity\Clients;
use App\Form\ClientType;
use App\Form\SkillsType;
use App\Form\MessageType;
use App\Entity\Candidatures;
use App\Service\UserService;
use App\Form\UserPasswordType;
use App\Service\FileUploadService;
use App\Repository\OffresRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CandidaturesRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

// use Symfony\Component\Validator\Constraints\Expression;

#[Route("/compte", 'user.')]
class UserController extends AbstractController
{
    /**
     * This controller allow us to edit user profile
     *
     * @param Request $request
     * @param Clients $client
     * @param UserService $userService
     * @param FileUploadService $fileUploadService
     * 
     * @return Response
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/edition-profil/{id}', name: 'edit', methods: ['GET', 'POST'] )]
    public function edit(
        Clients $user,
        Request $request,
        FileUploadService $fileUploadService,
        ParameterBagInterface $parameterBag,
        UserService $userService,
    ): Response {

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
            ];

            $cvFile = $form["cvFile"]->getData();

            // config/services.yaml on va chercher le repertoire ou stocker les images
            $cvFileDirectory = $parameterBag->get('cv.upload_directory');
            $cvFileName = null;

            if (null !== $cvFile) {
                $cvFileName = $fileUploadService->renameUploadedFile($cvFile, $cvFileDirectory);
                // $user->setCvFile($cvFileName);
                // $user->setCvName($cvFile->getClientOriginalName());
            }

            // On utilise le service UserService pour enregister le User
            $userService->updateUserFromForm($user, $formData, $cvFileName);

            $this->addFlash(
                'success',
                'Les informations de votre compte ont bien été modifiées.'
            );

            return $this->redirectToRoute('user.mesCandidatures');
        }

        return $this->render('pages/user/edit.html.twig', [
            'form' => $form,
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
     * This controller allow us to edit your password
     *
     * @param Users $user
     * @param Request $request
     * @param EntityManagerInterface $manager
     * 
     * @return Response
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/competences', 'add.competences', methods: ['GET', 'POST'])]
    public function competences(
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response {

        /** @var Clients $user */
        $user = $this->getUser() ;

        $skill = new Skills();

        $form = $this->createForm(SkillsType::class, $skill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setKills($skill->getNom())
            ;
    
            $entityManager->persist($skill);
            $entityManager->flush();

            $this->addFlash('success', 'Votre coimpétence a été créée avec succès !');

            return $this->redirectToRoute('skills.create', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pages/competences/ajouter-competences.html.twig', [
            'form' => $form,
        ]);
    }


    /**
     * This controller list all mission for the current Company
     * 
     * @param CandidaturesRepository $candidatureRepository
     * @param Request $request
     * @param Security $security
     * 
     * @return Response
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/mes-candidatures', name: 'mesCandidatures', methods: ['GET'])]
    public function mesCandidatures(
        CandidaturesRepository $candidatureRepository,
        Security $security,
        Request $request
    ): Response {

        /** @var Clients $user */
        $user   = $this->getUser();
        $userId = $user->getId() ;

        $page = $request->query->getInt('page', 1) ;

        $candidatures = $candidatureRepository->paginateCandidtatures($page, $userId) ;
    
        return $this->render('pages/user/mes-candidatures.html.twig', compact('candidatures'));
    }

    

}
