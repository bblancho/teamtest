<?php

namespace App\Service;

use App\Entity\Offres;
use App\Entity\Societes;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class OffreService
{
    /** @var EntityManagerInterface */
    protected EntityManagerInterface $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param FormInterface $form
     * @param UserInterface $user
     * @return Offres|null
     */
    public function createOffre(FormInterface $form, UserInterface $user): ?Offres
    {
        /** @var Offres $offre */
        $offre = $form->getData();

        if (!$user instanceof Societes) {
            throw new \LogicException('L\'utilisateur doit être une société.');
        }
        
        $societe = $user;

        // Validation
        if (null === $offre->getNom() || trim($offre->getNom()) === '') {
            $form->get('nom')->addError(new FormError('Veuillez renseigner le nom.'));
        }

        if (null === $offre->getDescription() || trim($offre->getDescription()) === '') {
            $form->get('description')->addError(new FormError('Veuillez renseigner la description.'));
        }

        if (null === $offre->getLieuMission() || trim($offre->getLieuMission()) === '') {
            $form->get('lieuMission')->addError(new FormError('Veuillez renseigner le lieu de la mission.'));
        }

        if (null === $offre->getDuree() || trim($offre->getDuree()) === '') {
            $form->get('duree')->addError(new FormError('Veuillez renseigner la durée.'));
        }

        if (null === $offre->getProfil() || trim($offre->getProfil()) === '') {
            $form->get('profil')->addError(new FormError('Veuillez renseigner le profil recherché.'));
        }

        // if (null === $offre->getRefMission() || trim($offre->getRefMission()) === '') {
        //     $form->get('refMission')->addError(new FormError('Veuillez renseigner la référence.'));
        // }

        if (!is_numeric($offre->getTarif()) || $offre->getTarif() <= 0) {
            $form->get('tarif')->addError(new FormError('Le tarif doit être un nombre positif.'));
        }

        if (null === $offre->getStartDateAT()) {
            $form->get('startDateAT')->addError(new FormError('Veuillez renseigner la date de début.'));
        }

        if (count($form->getErrors(true)) > 0) {
            return null;
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

        $offre
            ->setSocietes($societe)
            ->setNbCandidatures(0)
            ->setSlug($offre->getNom() . $offre->getRefMission())
        ;

        $this->entityManager->persist($offre);
        $this->entityManager->flush();

        return $offre;
    }
}