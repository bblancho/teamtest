<?php

namespace App\Controller;

use App\Entity\Offres;
use App\Form\OffreType;
use App\Entity\Societes;
use Symfony\Bundle\SecurityBundle\Security;
use App\Security\Voter\OffresVoter;
use App\Repository\OffresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route("/offres", 'offres.')]
class MissionController extends AbstractController
{   
    /**
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

        $page = $request->query->getInt('page', 1) ;
        $userId =  $security->getUser()->getId() ;

        // On limite l'affichage aux missions de la société, mettre OFFRE_LIST
        $canListAll = $security->isGranted(OffresVoter::OFFRE_ALL) ;
        $missions = $offresRepository->paginateOffres($page , $canListAll ? null : $userId) ;

        return $this->render('pages/missions/mes_missions.html.twig', compact( "missions") );
    }

    /**
     * This controller show a form which create an mission
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/creation', name: 'create', methods: ['GET', 'POST'])]
    #[IsGranted(OffresVoter::OFFRE_CREATE)]
    public function new(
        Request $request,
        EntityManagerInterface $manager
    ): Response {

        $mission = new Offres();

        $form = $this->createForm(OffreType::class, $mission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $mission->setSocietes($this->getUser());
            $mission->setSlug($form["nom"]->getData());

            $manager->persist($mission);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre mission a été créé avec succès !'
            );

            return $this->redirectToRoute('offres.mes_offres');
        }

        return $this->render('pages/missions/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * This controller allow us to see a recipe if this one is public
     *
     * @param OffresRepository $offresRepository
     * @return Response
     */
    #[Route('/client/{slug}-{id}', name: 'show', methods: ['GET'], requirements: ['id' => '\d+' , 'slug' => '[a-z0-9-]+'] )]
    #[IsGranted(OffresVoter::OFFRE_VIEW, subject: 'offre')]
    public function show(
        OffresRepository $offresRepository, int $id, string $slug , Offres $offre
    ): Response {

        $mission = $offresRepository->find($id);

        if( $mission->getSlug() != $slug){
            return $this->redirectToRoute('offre.show', ['slug' => $mission->getSlug() , 'id' => $mission->getId()]) ;
        }

        return $this->render('pages/missions/client_show.html.twig', [
            'mission' => $mission
        ]);
    }

    /**
     * This controller allow us to edit an ingredient
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
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $offre = $form->getData();

            $offre->setSlug($form["nom"]->getData());

            $manager->flush();

            $this->addFlash(
                'success',
                'Votre mission a été modifiée avec succès !'
            );

            return $this->redirectToRoute('offres.mes_offres');
        }

        return $this->render('pages/missions/edit.html.twig', [
            'offre' => $offre ,
            'form' => $form->createView()
        ]);
    }


    /**
     * This controller display all ingredients
     *
     * @param OffresRepository $offresRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/offres-enligne', name: 'en_ligne', methods: ['GET'])]
    public function offrePublie(
        OffresRepository $offresRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {

        $missions =  $paginator->paginate(
            $offresRepository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('pages/missions/index.html.twig', [
            'missions' => $missions
        ]);
    }

    /**
     * This controller display all ingredients
     *
     * @param OffresRepository $offresRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/offres-non-publiee', name: 'non_publiee', methods: ['GET'])]
    public function offreNonPublie(
        OffresRepository $offresRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {

        $missions =  $paginator->paginate(
            $offresRepository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('pages/missions/index.html.twig', [
            'missions' => $missions
        ]);
    }

    /**
     * 
     * @param OffresRepository $offresRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/societe/mes-offres-non-publiee', name: 'mes_offres_non_publiees', methods: ['GET'])]
    public function mesOffresNonPubliees(
        OffresRepository $offresRepository,
        PaginatorInterface $paginator,
        Request $request,
    ): Response {

        
        //$offres = $offresRepository->findBy(['societes' => $userId]) ;
    
       // $societe->getOffres(),

        // $missions = $paginator->paginate(
        //     $offres,
        //     $request->query->getInt('page', 1),
        //     10
        // );

        // dd($user) ;

        // $products = $OffresRepository->findBy(
        //     ['users_id' => $userId],
        //     ['id' => 'DESC']
        // );

        $missions ='';

        return $this->render('pages/missions/mes_missions_non_publiees.html.twig', [
            "missions" => $missions
        ]);
    }

    /**
     * @param OffresRepository $offresRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/societe/mes-offres-publiees', name: 'mes_offres_publiees', methods: ['GET'])]
    public function mesOffresPubliees(
        OffresRepository $offresRepository,
        PaginatorInterface $paginator,
        Request $request,
    ): Response {

        $missions ='';

        return $this->render('pages/missions/mes_missions_publiees.html.twig', [
            "missions" => $missions
        ]);
    }

    /**
     * This controller allows us to delete an ingredient
     *
     * @param EntityManagerInterface $manager
     * @param Offres $offre
     * @return Response
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/{id}/activer',  name: 'activer', requirements: ['id' => Requirement::DIGITS], methods: ['GET'] )]
    // #[Security("is_granted('ROLE_USER') and user === ingredient.getUser()")]
    public function activer(
        EntityManagerInterface $manager,
        Offres $offre
    ): Response {   

        $this->addFlash(
            'success',
            'Votre mission a été publiée avec succès !'
        );

    
        return $this->redirectToRoute('admin.offres.index');
    }

    /**
     * This controller allows us to delete an ingredient
     *
     * @param EntityManagerInterface $manager
     * @param Offres $offre
     * @return Response
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/{id}/desactiver',  name: 'desactiver', requirements: ['id' => Requirement::DIGITS], methods: ['GET'] )]
    // #[Security("is_granted('ROLE_USER') and user === ingredient.getUser()")]
    public function desactiver(
        EntityManagerInterface $manager,
        Offres $offre
    ): Response {

        $manager->flush();

        $this->addFlash(
            'success',
            'Votre mission a été désactivée avec succès !'
        );

        return $this->redirectToRoute('admin.offres.index');
    }

    /**
     * This controller allows us to delete an ingredient
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

        // On vérifie si l'utilisateur peut supprimer avec le Voter
        // $this->denyAccessUnlessGranted('PRODUCT_DELETE', $offre);

        $manager->remove($offre);
        $manager->flush();

        $this->addFlash(
            'success',
            'Votre mission a été supprimée avec succès !'
        );

        return $this->redirectToRoute('offres.mes_offres');
    }

}
