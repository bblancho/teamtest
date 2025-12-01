<?php

namespace App\Controller;

use App\Entity\Offres;
use App\Event\OfferPublishedEvent;
use App\Form\OffreType;
use App\Service\OffreService;
use App\Security\Voter\OffresVoter;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Mime\Address;
use App\Repository\OffresRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CandidaturesRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\ActiveTrailNotificationService;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

#[Route("/offres", 'offres.')]
class MissionController extends AbstractController
{   

/********************************* Crud Mission **************************************/

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
        $canListAll = $security->isGranted(OffresVoter::OFFRE_LIST) ;
        $missions   = $offresRepository->paginateOffres($page , $canListAll ? $userId : null) ;
        
        return $this->render('pages/missions/mes_missions.html.twig', compact( "missions") );
    }

    /**
     * This method allow us to create an mission
     *
     * @param Request $request
     * @param OffreService $offreService
     * @return Response
     */
    #[IsGranted('ROLE_SOCIETE')]
    #[Route('/creation', name: 'create', methods: ['GET', 'POST'])]
    #[IsGranted(OffresVoter::OFFRE_CREATE)]
    public function create(
        Request $request, 
        OffreService $offreService,
        ActiveTrailNotificationService $activeTrail,
        EventDispatcherInterface $dispatcher
    ): Response{
        
        $offre = new Offres();

        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $result = $offreService->createOffre($form, $this->getUser());

            // if ($result !== null) {
                $this->addFlash('success', 'Votre mission a été créée avec succès !');

           //  dd($form->getData());
                $activeTrail->sendNotification(
                    $form->get('nom')->getData() ,
                    $form->get('description')->getData()
                );

            // dispatch event
            // $event = new OfferPublishedEvent($form->getData());
            // $dispatcher->dispatch($event, OfferPublishedEvent::class);

                return $this->redirectToRoute('offres.mes_offres');
            // }
        }

        return $this->render(
            'pages/missions/new.html.twig',
            [
                'form' => $form
            ]
        );
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
                $offre->setSlug($form["nom"]->getData());
            }

            $publier = $form["isActive"]->getData() ;

            if($publier == true)
            {
                $offre
                    ->setIsArchive(false)
                    ->setIsActive(true)
                ;

            }else{
                $offre
                    ->setIsArchive(true)
                    ->setIsActive(false)
                ;
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
    #[IsGranted('ROLE_SOCIETE')]
    #[Route('/{id}/activer',  name: 'activer_offre', requirements: ['id' => Requirement::DIGITS], methods: ['GET'] )]
    #[IsGranted(OffresVoter::OFFRE_EDIT, subject: 'offre')]
    public function publierOffre(
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
            ->setIsActive(true)
        ;
    
        $manager->flush();

        $this->addFlash(
            'success',
            'Votre mission a été activée.'
        );

        return $this->redirectToRoute('offres.mes_offres');
    }

    /**
     * This method allows us to archive an mission
     *
     * @param EntityManagerInterface $manager
     * @param Offres $offre
     * @return Response
     */
    #[IsGranted('ROLE_SOCIETE')]
    #[Route('/{id}/archiver-offre',  name: 'depublier_offre', requirements: ['id' => Requirement::DIGITS], methods: ['GET'] )]
    #[IsGranted(OffresVoter::OFFRE_EDIT, subject: 'offre')]
    public function depublierOffre(
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
            'Votre mission a été dépubliée avec succès.'
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
    #[IsGranted('ROLE_SOCIETE')]
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

/********************************* Gestion des Candidatures **************************************/

    /**
     * Cette fonction affiche la liste des candidatures d'une offre
     *
     * @param Users $choosenUser
     * @param Request $request
     * @return Response
     */
    #[IsGranted('ROLE_SOCIETE')]
    #[Route('/candidatures/offre-{id}-{slug}', name: 'candidaturesOffre', methods: ['GET'], requirements: ['id' => Requirement::DIGITS, 'slug' => Requirement::ASCII_SLUG])]
    public function listeCandidaturesOffre(
        CandidaturesRepository $candidaturesRepository, 
        Request $request,
        Offres $mission,
        string $slug ,
    ): Response {
        $page = $request->query->getInt('page', 1) ;

        $candidatures  = $candidaturesRepository->paginateOffreCandidatures($page, $mission, null);
        
        if( $mission->getSlug() != $slug ){
            return $this->redirectToRoute('offre.show', ['slug' => $mission->getSlug() , 'id' => $mission->getId()]) ;
        }

        return $this->render('pages/missions/candidatures_offre.twig', compact("mission", "candidatures") );
    }

    /**
     * Cette fonction affiche la liste des candidatures acceptées pour une offre
     *
     * @param Users $choosenUser
     * @param Request $request
     * @return Response
     */
    #[IsGranted('ROLE_SOCIETE')]
    #[Route('/candidatures/offre-{id}-{slug}/validee', name: 'candidatures_offre_validees', methods: ['GET'], requirements: ['id' => Requirement::DIGITS, 'slug' => Requirement::ASCII_SLUG])]
    public function listeCandidaturesOffreValidees(
        CandidaturesRepository $candidaturesRepository, 
        Request $request,
        Offres $mission,
        string $slug ,
    ): Response {
        $page = $request->query->getInt('page', 1) ;

        $candidatures  = $candidaturesRepository->paginateOffreCandidaturesValidee($page, $mission);
        
        if( $mission->getSlug() != $slug ){
            return $this->redirectToRoute('offre.show', ['slug' => $mission->getSlug() , 'id' => $mission->getId()]) ;
        }
    
        return $this->render('pages/missions/candidatures_valide.twig', compact("mission", "candidatures") );
    }

    /**
     * Cette fonction valide une candiature et envoie un mail au free-lance
     *
     * @param EntityManagerInterface $manager
     * @param CandidaturesRepository $candidaturesRepository
     * @return Response
     * @throws TransportExceptionInterface
     */
    #[IsGranted('ROLE_SOCIETE')]
    #[Route('/{id}/valider-candidature', name: 'valider.candidature', methods: ['GET'])]
    public function validerCandidature(
        EntityManagerInterface $manager,
        CandidaturesRepository $candidaturesRepository, 
        int $id,
        MailerInterface $mailer
    ): Response {

        $candidature = $candidaturesRepository->find($id);
        $mission     = $candidature->getOffres();

        if (!$candidature) {
            throw $this->createNotFoundException(
                'Aucune candidature trouvée pour cet id '.$id
            );
        }

        $candidature
            ->setRetenue(true) 
            ->setConsulte(true)
        ;

        // je récupere l'ensemble des candidatures pour l'offre à laquelle on a postulé
            $nbCandidatures = $candidaturesRepository->nbCandidatures($mission) ;
            $nbCandidatures = $nbCandidatures - 1;

        // je rajoute la +1 au champs nb candidature de l'entité offre
            if( $nbCandidatures <= 0 ){
                $nbCandidatures = 0 ;
                $mission->setNbCandidatures($nbCandidatures) ;
            }

        // Envoie du mail au candidat
            $client = $candidature->getClients();
            
            $email = (new TemplatedEmail())
                ->from(new Address('team2i@gmail.com', 'Team2i'))
                ->to((string) $client->getEmail())
                ->subject('Acceptation de candidature')
                ->htmlTemplate('emails/acceptation.html.twig')
                ->context([
                    'client' => $client,
                    'offre' => $candidature->getOffres(),
                ])
            ;

            $mailer->send($email);

        $manager->flush();

        $this->addFlash(
            'success',
            'Le mail de validation  pour la candidature a bien été envoyé.'
        );

        return $this->redirectToRoute('offres.candidaturesOffre', ['id' => $mission->getId(), 'slug' => $mission->getSlug() ]);
    }

    /**
     * This method allows us to delete an mission
     *
     * @param EntityManagerInterface $manager
     * @param CandidaturesRepository $candidaturesRepository
     * @return Response
     */
    #[IsGranted('ROLE_SOCIETE')]
    #[Route('/candidature/{id}/refusee', 'refuser.candidature', methods: ['GET'])]
    public function refuserCandidature(
        EntityManagerInterface $manager,
        CandidaturesRepository $candidaturesRepository, 
        int $id,
    ): Response {

        $candidature = $candidaturesRepository->find($id);
        $mission = $candidature->getOffres();

        if (!$candidature) {
            throw $this->createNotFoundException(
                'Aucune candidature trouvée pour cet id '.$id
            );
        }

        $candidature
            ->setRetenue(false) 
            ->setConsulte(true)
        ;

        // je récupere l'ensemble des candidatures pour l'offre à laquelle on a postulé
            $nbCandidatures = $candidaturesRepository->nbCandidatures($mission) ;
            $nbCandidatures = $nbCandidatures - 1;

        // je rajoute la +1 au champs nb candidature de l'entité offre
            if( $nbCandidatures <= 0 ){
                $nbCandidatures = 0 ;
                $mission->setNbCandidatures($nbCandidatures) ;
            }
            
        $manager->flush();

        $this->addFlash(
            'success',
            'La candidature a été traitée avec succès .'
        );

        return $this->redirectToRoute('offres.candidaturesOffre', ['id' => $mission->getId(), 'slug' => $mission->getSlug() ]);
    }

    
    #[IsGranted('ROLE_SOCIETE')]
    #[Route('/candidature/{slug}-{id}', name: 'user.candidature', methods: ['GET'], requirements: ['id' => '\d+' , 'slug' => '[a-z0-9-]+'] )]
    public function showCandidature(
        CandidaturesRepository $candidaturesRepository, 
        int $id, 
        string $slug,
    ): Response{

        $candidature = $candidaturesRepository->find($id);
        $id     = $candidature->getId();

        if (!$candidature) {
            throw $this->createNotFoundException(
                'Aucune offre trouvée pour cet id '.$id
            );
        }

        if( $candidature->getSlug() != $slug){
            return $this->redirectToRoute('app_show_offre', ['slug' => $candidature->getSlug() , 'id' => $candidature->getId()]) ;
        }

        return $this->render('pages/missions/user_candidature.html.twig', compact('candidature'));
    }

    
/********************************* Gestion des Archives **************************************/

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
            $canListAll = $security->isGranted(OffresVoter::OFFRE_LIST) ;
            $missions   = $offresRepository->paginateOffresArchives($page , $canListAll ? $userId : null ) ;

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
        #[Route('/{id}/archiver-offre',  name: 'archiver_offre', requirements: ['id' => Requirement::DIGITS], methods: ['GET'] )]
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

        /**
         * This method allows us to archive an mission
         *
         * @param EntityManagerInterface $manager
         * @param Offres $offre
         * @return Response
         */
        #[IsGranted('ROLE_USER')]
        #[Route('/desarchiver-offre/{id}',  name: 'desarchiver_offre',requirements: ['id' => Requirement::DIGITS], methods: ['GET'] )]
        #[IsGranted(OffresVoter::OFFRE_EDIT, subject: 'offre')]
        public function desarchiver(
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
                'Votre mission a été desarchivée avec succès.'
            );

            return $this->redirectToRoute('offres.mes_offres');
        }

        
}
