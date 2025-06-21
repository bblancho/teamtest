<?php

namespace App\Controller;

use App\Entity\Societes;
use App\Form\SocieteType;
use App\Form\UserPasswordType;
use App\Repository\OffresRepository;
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

class SocieteController extends AbstractController
{
    
    /**
     * This controller allow us to edit user's profile
     *
     * @param Societes $user
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/societe/edition/', name: 'societe.edit', methods: ['GET', 'POST'])]
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
    #[Route('/societe/edition-mot-de-passe', 'societe.edit.password', methods: ['GET', 'POST'])]
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

    /**
     * This controller allow us to edit user's profile
     *
     * @param Users $choosenUser
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/candidature/offre-{id}-{slug}', name: 'user.candidature', methods: ['GET'], requirements: ['id' => Requirement::DIGITS, 'slug' => Requirement::ASCII_SLUG])]
    public function candidature(
        OffresRepository $offresRepository, 
        int $id, 
        string $slug ,
        EntityManagerInterface $manager
    ): Response {
        
        $mission    = $offresRepository->find($id);
        $freeLance  = $this->getUser() ;

        if( $mission->getSlug() != $slug ){
            return $this->redirectToRoute('offre.show', ['slug' => $mission->getSlug() , 'id' => $mission->getId()]) ;
        }

        // On vérifie que le user n'ai pas déjà postulé
            // On fait la redirection sur la page show
        // On crée notre objet Candidature
        // On le sauvegarde
        // On fait la redirection sur la page show + message

        return $this->redirectToRoute('offre.show', ['slug' => $mission->getSlug() , 'id' => $mission->getId()]) ;
    }

}
