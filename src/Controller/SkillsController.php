<?php

namespace App\Controller;

use App\Entity\Skills;
use App\Form\SkillsType;
use App\Security\Voter\OffresVoter;
use App\Repository\SkillsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/skills', 'skills.')]
final class SkillsController extends AbstractController
{
    #[Route(path:"/", name: 'index', methods: ['GET'])]
    #[IsGranted(OffresVoter::OFFRE_CREATE)]
    public function index(
        SkillsRepository $skillsRepository,
        Request $request,
    ): Response
    {   
        $page = $request->query->getInt('page', 1) ;

        $skills = $skillsRepository->paginateOffres($page) ;

        return $this->render('pages/skills/index.html.twig', [
            'skills' => $skills,
        ]);
    }

    #[Route(path: '/creation', name: 'create', methods: ['GET', 'POST'])]
    #[IsGranted(OffresVoter::OFFRE_CREATE)]
    public function new(
        Request $request, 
        EntityManagerInterface $entityManager
    ): Response
    {
        $skill = new Skills();

        $form = $this->createForm(SkillsType::class, $skill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nom  = trim($form['nom']->getData()) ;
            $slug = strtolower($nom) ;

            $skill
                ->setUsers( $this->getUser() )
                ->setContent( trim($form['content']->getData()) )
                ->setNom($slug)
                ->setSlug($skill->getNom())
            ;
    
            $entityManager->persist($skill);
            $entityManager->flush();

            $this->addFlash('success', 'Votre compétence a été créée avec succès !');

            return $this->redirectToRoute('skills.create', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pages/skills/new.html.twig', [
            'skill' => $skill,
            'form' => $form,
        ]);
    }

    #[Route(path: '/{id}', name: 'show', methods: ['GET'])]
    public function show(Skills $skill): Response
    {
        return $this->render('pages/skills/show.html.twig', [
            'skill' => $skill,
        ]);
    }

    #[Route(path: '/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Skills $skill, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SkillsType::class, $skill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('skills.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pages/skills/edit.html.twig', [
            'skill' => $skill,
            'form' => $form,
        ]);
    }

    #[Route(path: '/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Skills $skill, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$skill->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($skill);
            $entityManager->flush();
        }

        return $this->redirectToRoute('skills.index', [], Response::HTTP_SEE_OTHER);
    }
}
