<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\Security\Voter\OffresVoter;
use App\Repository\OffresRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{

    #[Route('/home', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * 
     * @param OffresRepository $offresRepository
     * @param Request $request
     * @param Security $security
     * 
     * @return Response
     */
    #[Route('/', name: 'app_index')]
    public function home(OffresRepository $offresRepository, Request $request, Security $security): Response
    {   
        $page = $request->query->getInt('page', 1) ;

        $canListAll = $security->isGranted(OffresVoter::OFFRE_ALL) ;

        $userId =  $security->getUser()->getId() ;

        // On limite la liste des offres à celle de l'utilisateur si il n'a pas les permissions de tout voir
        $missions = $offresRepository->paginateOffres($page , $canListAll ? null : $userId) ;

        return $this->render('pages/missions/index.html.twig', compact('missions'));
    }
}
