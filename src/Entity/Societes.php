<?php

namespace App\Entity;

use App\Entity\Users;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\SocietesRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Entity\File as EmbeddedFile;

#[ORM\Entity(repositoryClass: SocietesRepository::class)]
#[ORM\Table(name: "societes")]
class Societes extends Users
{
    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: "Le nom doit faire minimum {{ limit }} caractères.",
        maxMessage: "Le nom doit faire au maximum {{ limit }} caractères."
    )]
    private ?string $nomContact = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $numContact = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank()]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank()]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: "Le secteur d'activité doit faire minimum  {{ limit }} caractères .",
        maxMessage: "Le secteur d'activité doit faire au maximum  {{ limit }} caractères .",
    )]
    private ?string $secteurActivite = null;
    
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $phoneContact = null;

    public function getNomContact(): ?string
    {
        return $this->nomContact;
    }

    public function setNomContact(?string $nomContact): static
    {
        $this->nomContact = $nomContact;

        return $this;
    }

    public function getNumContact(): ?string
    {
        return $this->numContact;
    }

    public function setNumContact(?string $numContact): static
    {
        $this->numContact = $numContact;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }
    
    public function getSecteurActivite(): ?string
    {
        return $this->secteurActivite;
    }

    public function setSecteurActivite(?string $secteurActivite): static
    {
        $this->secteurActivite = $secteurActivite;

        return $this;
    }

    public function getPhoneContact(): ?string
    {
        return $this->phoneContact;
    }

    public function setPhoneContact(?string $phoneContact): static
    {
        $this->phoneContact = $phoneContact;

        return $this;
    }


}
