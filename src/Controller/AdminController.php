<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Offres;
use App\Form\UserType;
use App\Entity\Clients;
use App\Form\OffreType;
use App\Entity\Societes;
use App\Form\ClientType;
use App\Form\UserPasswordType;
use App\Form\CreateSocieteFormType;
use App\Repository\OffresRepository;
use App\Repository\ClientsRepository;
use App\Repository\SocietesRepository;
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

#[Route("/admin", 'admin.')]
class AdminController extends AbstractController
{

    /**
     * @param SocietesRepository $societesRepository
     * @param Request $request
     * 
     * @return Response
     */
    #[Route('/', name: 'app_admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response 
    {
        return $this->render('admin/admin.html.twig' );
    }

    /**
     * @param SocietesRepository $societesRepository
     * @param Request $request
     * 
     * @return Response
     */
    #[Route('/societes', name: 'app_admin_societes')]
    #[IsGranted('ROLE_ADMIN')]
    public function indexSociete(
        SocietesRepository $societesRepository,
        Request $request,
    ): Response {

        $page = $request->query->getInt('page', 1) ;

        $societes = $societesRepository->paginateSocietes($page) ;

        return $this->render('admin/societes/index.html.twig' );
    }

    /**
     * @param SocietesRepository $societesRepository
     * @param Request $request
     * 
     * @return Response
     */
    #[Route('/free-lance', name: 'app_admin_freelance')]
    #[IsGranted('ROLE_ADMIN')]
    public function indexFreelance(
        ClientsRepository $clientsRepository,
        Request $request,
    ): Response {

        $page = $request->query->getInt('page', 1) ;

        $freelances = $clientsRepository->paginateFreelances($page) ;

        dd($freelances) ;

        return $this->render('admin/admin.html.twig' );
    }

    /**
     * This controller allow us to register.
     */
    #[Route('/creation', 'create_Societe', methods: ['GET', 'POST'])]
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
    #[Route('/creation-offre/{societe}', name: 'create_offre', methods: ['GET', 'POST'])]
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
    

}
