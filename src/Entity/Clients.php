<?php

namespace App\Entity;

use App\Entity\Users;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClientsRepository;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ClientsRepository::class)]
#[ORM\Table(name: "clients")]
class Clients extends Users
{
    #[ORM\Column(nullable: true)]
    #[Assert\Type(
        type: 'integer',
        message: "La valeur {{ value }} n'est pas de {{ type }}.",
    )]
    private ?int $tjm = null;

    #[ORM\Column(nullable: true)]
    private ?bool $dispo = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dateDispoAt = null;

    public function getTjm(): ?int
    {
        return $this->tjm;
    }

    public function setTjm(int $tjm): static
    {
        $this->tjm = $tjm;

        return $this;
    }

    public function getDateDispoAt(): ?\DateTimeImmutable
    {
        return $this->dateDispoAt;
    }

    public function setDateDispoAt(?\DateTimeImmutable $dateDispoAt): static
    {
        $this->dateDispoAt = $dateDispoAt;

        return $this;
    }

    public function isDispo(): ?bool
    {
        return $this->dispo;
    }

    public function setDispo(?bool $dispo): static
    {
        $this->dispo = $dispo;

        return $this;
    }


    
}
