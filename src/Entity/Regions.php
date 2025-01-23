<?php

namespace App\Entity;

use Assert\Regex;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\RegionsRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: RegionsRepository::class)]
#[UniqueEntity('slug')]
#[UniqueEntity('nom')]
class Regions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\Length(
        min: 2,
        max: 50,
    )]
    private ?string $nom = null;

    #[ORM\Column(length: 50)]
    #[Assert\Length(min: 3)]
    #[Assert\Regex("/^[a-z0-9]+(?:-[a-z0-9]+)*$/", message: "Invalid Slug")]
    private ?string $slug = null;

    #[ORM\PrePersist()]
    public function prePresist(){
        $this->slug = (new Slugify())->slugify($this->nom) ;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = (new Slugify())->slugify($slug) ;

        return $this;
    }

}
