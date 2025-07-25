<?php

namespace App\Controller;

use App\Entity\Offres;
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
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    /**
     * 
     * @param OffresRepository $offresRepository
     * @param Request $request
     * @param Security $security
     * 
     * @return Response
     */
    #[Route('/', name: 'app_index')]
    public function index(
        OffresRepository $offresRepository,
        Request $request,
        Security $security
    ): Response {

        $page = $request->query->getInt('page', 1) ;
        $userId =  null ;

        $missions   = $offresRepository->paginateOffres($page , $userId) ;

        return $this->render('pages/missions/index.html.twig', compact( "missions") );
    }

    /**
     * Affiche une mission
     *
     * @param OffresRepository $offresRepository
     * @return Response
     */
    #[Route('/client/{slug}-{id}', name: 'app_show_offre', methods: ['GET'], requirements: ['id' => '\d+' , 'slug' => '[a-z0-9-]+'] )]
    public function show(
        OffresRepository $offresRepository, 
        CandidaturesRepository $candidaturesRepository, 
        int $id, 
        string $slug
    ): Response {

        $mission = $offresRepository->find($id);

        if( $mission->getSlug() != $slug){
            return $this->redirectToRoute('app_show_offre', ['slug' => $mission->getSlug() , 'id' => $mission->getId()]) ;
        }

        $freeLance = $this->getUser() ;

        // Actuellement on ne rentre jamais dedans si on est pas conneccté
        if( $this->isGranted('ROLE_CLIENT') )
        {
           // On vérifie si le user a déjà postulé
            $candidature = $candidaturesRepository->aDejaPostule($freeLance, $mission);
            // dd($candidature) ;
            if( $candidature != null ){
                $aDejaPostule = true ;
            }else{
                $aDejaPostule = false ; 
            }

        } else {
    
            $aDejaPostule = true ;
            $candidature = true;
        }

        return $this->render('pages/missions/show.html.twig', 
            compact('mission', 'aDejaPostule', 'candidature')
        );
    }

    /**
     * This controller allow us to see a recipe if this one is public
     *
     * @param OffresRepository $offresRepository
     * @return Response
     */
    #[Route('/import/ville', name: 'app_import_ville', methods: ['GET'] )]
    public function import(
        OffresRepository $offresRepository
    ): Response {

        $file = fopen(__DIR__.'/../import/citiesexport_2.csv', 'r') ;
        $line = fgetcsv($file, null, ","); // On parcourt l'entête du fichier
        $line = fgetcsv($file, null, ","); // On parcourt l'entête du fichier
        $line = fgetcsv($file, null, ","); // On parcourt l'entête du fichier
        $line = fgetcsv($file, null, ","); // On parcourt l'entête du fichier
        dd($line) ;
        $line = fgetcsv($file, null, ","); 
        
        while( ($line = fgetcsv($file, null, ",")) !== false )
        {
            $cityName = $line[1] ; // string
            $latitude = $line[2] ; // string
            $longitude = $line[3] ; // string
            $date = $line[4] ; // string
            $temp = $line[5] ; // string
            
            dump($cityName, $latitude, $longitude, $date, $temp ) ;

            // ensuite on procède à l'insertion
        }


        return $this->render('pages/missions/show.html.twig', [
            'mission' => $mission
        ]);
    }

    /**
     * On va lister les missions d'une société
     *
     * @return Response
     */
    #[Route('/societe-{slug}', name: 'app_societe_offres', methods: ["GET"])]
    public function offresSociete(): Response
    {

        return $this->render('pages/missions/show.html.twig');
    }

    
}
