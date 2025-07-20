<?php

namespace App\Controller;

use App\Entity\Offres;
use App\Form\OffreType;
use App\Entity\Societes;
use App\Entity\Candidatures;
use App\Security\Voter\OffresVoter;
use App\Repository\OffresRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CandidaturesRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route("/offres", 'offres.')]
class MissionController extends AbstractController
{   
    /*********************************  Crud Mission **************************************/

    /**
     * This controller list all mission for the current Company
     * 
     * @param OffresRepository $offresRepository
     * @param Request $request
     * @param Security $security
     * 
     * @return Response
     */
    #[Route('/societe/mes-offres', name: 'mes_offres', methods: ['GET'])]
    #[IsGranted(OffresVoter::OFFRE_LIST)]
    public function mesOffres(
        OffresRepository $offresRepository,
        Request $request,
        Security $security
    ): Response {

        $page   = $request->query->getInt('page', 1) ;
        $userId =  $security->getUser()->getId() ;

        // On limite l'affichage aux missions de la société
        $canListAll = $security->isGranted(OffresVoter::OFFRE_LIST_ALL) ;
        $missions   = $offresRepository->paginateOffres($page , $canListAll ? null : $userId) ;

        // dd($missions) ;

        return $this->render('pages/missions/mes_missions.html.twig', compact( "missions") );
    }

    /**
     * This method allow us to create an mission
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/creation', name: 'create', methods: ['GET', 'POST'])]
    #[IsGranted(OffresVoter::OFFRE_CREATE)]
    public function create(
        Request $request,
        EntityManagerInterface $manager
    ): Response {

        $mission = new Offres();

        $form = $this->createForm(OffreType::class, $mission);
        $form->handleRequest($request);

        if (  $form->isSubmitted() && $form->isValid()  ) {

            $mission->setSocietes( $this->getUser() ) ;
            $mission->setNbCandidatures(0) ;
            $mission->setSlug($form["nom"]->getData().$form["refMission"]->getData()) ;

            $manager->persist($mission);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre mission a été créé avec succès !'
            );

            return $this->redirectToRoute('offres.mes_offres');
        }

        return $this->render('pages/missions/new.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * This method allow us to edit an mission
     *
     * @param Offres $offre
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/edition/{id}', 'edit', methods: ['GET', 'POST'])]
    #[IsGranted(OffresVoter::OFFRE_EDIT, subject: 'offre')]
    public function edit(
        Offres $offre,
        Request $request,
        EntityManagerInterface $manager
    ): Response {

        $form = $this->createForm(OffreType::class, $offre);

        $hold_name = $offre->getNom() ;
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) {
            
            if( $hold_name !== $form["nom"]->getData()  ){
                $offre->setSlug($form["nom"]->getData().$form["refMission"]->getData());
            }

            $manager->flush();

            $this->addFlash(
                'success',
                'Votre mission a été modifiée avec succès !'
            );

            return $this->redirectToRoute('offres.mes_offres');
        }

        return $this->render('pages/missions/edit.html.twig', [
            'offre' => $offre ,
            'form' => $form
        ]);
    }

    /**
     * This method allows us to delete an mission
     *
     * @param EntityManagerInterface $manager
     * @param Offres $offre
     * @return Response
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/{id}/activer',  name: 'activer_offre', requirements: ['id' => Requirement::DIGITS], methods: ['GET'] )]
    #[IsGranted(OffresVoter::OFFRE_EDIT, subject: 'offre')]
    public function activerOffre(
        EntityManagerInterface $manager,
        OffresRepository $offresRepository,
        Offres $offre
    ): Response {   

        $offre  = $offresRepository->find($offre);
        $id     = $offre->getId();

            if (!$offre) {
                throw $this->createNotFoundException(
                    'Aucune offre trouvée pour cet id '.$id
                );
            }

        $offre
            ->setIsArchive(false)
            ->setIsActive(false)
        ;
    
        $manager->flush();

        $this->addFlash(
            'success',
            'Votre mission a été activée.'
        );

        return $this->redirectToRoute('offres.mes_offres');
    }

    /**
     * This method allows us to delete an mission
     *
     * @param EntityManagerInterface $manager
     * @param Offres $offre
     * @return Response
     */
    #[Route('/suppression/{id}', 'delete', methods: ['POST'])]
    #[IsGranted(OffresVoter::OFFRE_DELETE, subject: 'offre')]
    public function delete(
        EntityManagerInterface $manager,
        Offres $offre
    ): Response {

        $manager->remove($offre);
        $manager->flush();

        $this->addFlash(
            'success',
            'Votre mission a été supprimée avec succès !'
        );

        return $this->redirectToRoute('offres.mes_offres');
    }

    /*********************************  Candidatures **************************************/

        /**
         * This controller allow us to edit user's profile
         *
         * @param Users $choosenUser
         * @param Request $request
         * @param EntityManagerInterface $manager
         * @return Response
         */
        #[IsGranted('ROLE_USER')]
        #[Route('/candidature/offre-{id}-{slug}', name: 'candidaturesOffre', methods: ['GET'], requirements: ['id' => Requirement::DIGITS, 'slug' => Requirement::ASCII_SLUG])]
        public function listeCandidaturesOffre(
            CandidaturesRepository $candidaturesRepository, 
            Request $request,
            Offres $mission,
            string $slug ,
        ): Response {
            $page = $request->query->getInt('page', 1) ;

            $candidatures  = $candidaturesRepository->paginateOffreCandidatures($page, $mission);
            
            if( $mission->getSlug() != $slug ){
                return $this->redirectToRoute('offre.show', ['slug' => $mission->getSlug() , 'id' => $mission->getId()]) ;
            }

            return $this->render('pages/missions/candidatures_offre.twig', compact("mission", "candidatures") );
        }

        /**
         * This method allows us to delete an mission
         *
         * @param EntityManagerInterface $manager
         * @param Offres $offre
         * @return Response
         */
        #[Route('/gestion-candidature/{id}/{etat}', name: 'gestion.candidature', methods: ['GET'])]
        public function gestionCandidature(
            EntityManagerInterface $manager,
            CandidaturesRepository $candidaturesRepository, 
            Request $request,
            int $id, 
            bool $etat
        ): Response {

            $candidature = $candidaturesRepository->find($id);

            dd($request->request->all());

            if (!$candidature) {
                throw $this->createNotFoundException(
                    'Aucune candidature trouvée pour cet id '.$id
                );
            }

            $candidature = $candidaturesRepository->gestionCandidature($candidature, $etat);
            $candidature
                    ->setRetenue('New product name!')
                    ->setConsulte(true)
            ;
            
            $manager->flush();

            $this->addFlash(
                'success',
                'La candidature a été traitée avec succès !'
            );

            return $this->redirectToRoute('offres.candidaturesOffre', ["id" => $candidature , "etat" => true ]);
        }

    
    /*********************************  Archives **************************************/

        /**
         * This controller list all mission archives for the current Company
         * 
         * @param OffresRepository $offresRepository
         * @param Request $request
         * @param Security $security
         * 
         * @return Response
         */
        #[Route('/societe/mes-offres-archives', name: 'mes_offres_archives', methods: ['GET'])]
        #[IsGranted(OffresVoter::OFFRE_LIST)]
        public function mesOffresArchives(
            OffresRepository $offresRepository,
            Request $request,
            Security $security
        ): Response {

            $page   = $request->query->getInt('page', 1) ;
            $userId =  $security->getUser()->getId() ;

            // On limite l'affichage aux missions de la société
            $canListAll = $security->isGranted(OffresVoter::OFFRE_LIST_ALL) ;
            $missions   = $offresRepository->paginateOffresArchives($page , $canListAll ? null : $userId) ;

            $name = $request->get('_route'); // This will return the name.

            return $this->render('pages/missions/archives_missions.html.twig', compact( "missions") );
        }

        /**
         * This method allows us to archive an mission
         *
         * @param EntityManagerInterface $manager
         * @param Offres $offre
         * @return Response
         */
        #[IsGranted('ROLE_USER')]
        #[Route('/{id}/archiver-offre',  name: 'archiver_offre', methods: ['GET'] )]
        #[IsGranted(OffresVoter::OFFRE_EDIT, subject: 'offre')]
        public function archiver(
            EntityManagerInterface $manager,
            OffresRepository $offresRepository,
            Offres $offre 
        ): Response {   

            $offre  = $offresRepository->find($offre);
            $id     = $offre->getId();

            if (!$offre) {
                throw $this->createNotFoundException(
                    'Aucune offre trouvée pour cet id '.$id
                );
            }

            $offre
                ->setIsArchive(true)
                ->setIsActive(false)
            ;

            $manager->flush();

            $this->addFlash(
                'success',
                'Votre mission a été archivée avec succès.'
            );

            return $this->redirectToRoute('offres.mes_offres');
        }

    
}
