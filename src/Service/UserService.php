<?php

namespace App\Service;

use App\Entity\Clients;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class UserService
{
    protected EntityManagerInterface $entityManager;
    protected Security $security;

    public function __construct(
        Security $security,
        EntityManagerInterface $entityManager,
    ){
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    /**
     * @return UserInterface|null
     */
    public function getUser()
    {
        // returns User object or null if not authenticated
        return $this->security->getUser();
    }

    /**
     * @param Clients $user
     * @param array $formData
     * @param string|null $cvFileName
     * @return void
     */
    public function updateUserFromForm(Clients $user, array $formData, ?string $cvFileName = null): void
    {
        $user->setNom($formData['nom']);
        $user->setAdresse($formData['adresse']);
        $user->setCp($formData['cp']);
        $user->setVille($formData['ville']);
        $user->setPhone($formData['phone']);
        $user->setTjm($formData['tjm']);
        $user->addSkill($formData['skills']);
        $user->setIsNewsletter($formData['isNewsletter']);

        if($cvFileName != null){
            $user->setCvName($cvFileName);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
