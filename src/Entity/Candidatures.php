<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CandidaturesRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: CandidaturesRepository::class)]
#[UniqueEntity(
    fields: ['offres', 'clients'],
    message: 'Vous avez déjà postulé à cette offre.',
)]
class Candidatures
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Offres::class,inversedBy: 'candidatures')]
    #[ORM\JoinColumn(nullable: false)]
    private Offres $offres  ;

    #[ORM\ManyToOne(targetEntity: Clients::class , inversedBy: 'candidatures')]
    #[ORM\JoinColumn(nullable: false)]
    private Clients $clients ;

    #[ORM\Column(nullable: true)]
    private ?bool $consulte = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isRetenue = null;

    #[ORM\Column(length: 60)]
    private ?string $slug = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOffres(): ?Offres
    {
        return $this->offres;
    }

    public function setOffres(?Offres $offres): static
    {
        $this->offres = $offres;

        return $this;
    }

    public function getClients(): ?Clients
    {
        return $this->clients;
    }

    public function setClients(?Clients $clients): static
    {
        $this->clients = $clients;

        return $this;
    }

    public function isConsulte(): ?bool
    {
        return $this->consulte;
    }

    public function setConsulte(?bool $consulte): static
    {
        $this->consulte = $consulte;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function isRetenue(): ?bool
    {
        return $this->isRetenue;
    }

    public function setRetenue(?bool $isRetenue): static
    {
        $this->isRetenue = $isRetenue;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }
}
