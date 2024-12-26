<?php

namespace App\Entity;

use App\Repository\SocietesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SocietesRepository::class)]
#[ORM\Table(name: "societes")]
class Societes extends Users
{

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }
}
