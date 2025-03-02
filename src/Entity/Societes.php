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
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Vich\UploaderBundle\Entity\File as EmbeddedFile;


#[ORM\Entity(repositoryClass: SocietesRepository::class)]
#[ORM\Table(name: "societes")]
#[UniqueEntity('siret')]
#[Vich\Uploadable]
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

    #[Vich\UploadableField(
        mapping: 'societes', # We will remember this value. It will serve as the identifier for the section in the configuration.
        fileNameProperty: 'imageName',
    )]
    private ?File $imageFile = null;
    
    #[ORM\Column(length: 255, nullable: true)]
    private ?string  $imageName = null;

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

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()] 
    // #[Assert\Length(
    //     exactly: 14,
    //     exactMessage: "Le numéro de SIRET doit faire {{ limit }} caractères."
    // )]
    private string $siret = " ";

    /**
     * @var Collection<int, Offres>
     */
    #[ORM\OneToMany(targetEntity: Offres::class, mappedBy: 'societes', orphanRemoval: true)]
    private Collection $offres;

    public function __construct()
    {
        $this->offres = new ArrayCollection();
    }

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

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile ): static
    {
        $this->imageFile = $imageFile;

        return $this ;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): static
    {
        $this->imageName = $imageName;

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

    public function getSiret(): string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): static
    {
        $this->siret = $siret;

        return $this;
    }

    /**
     * @return Collection<int, Offres>
     */
    public function getOffres(): Collection
    {
        return $this->offres;
    }

    public function addOffre(Offres $offre): static
    {
        if (!$this->offres->contains($offre)) {
            $this->offres->add($offre);
            $offre->setSocietes($this);
        }

        return $this;
    }

    public function removeOffre(Offres $offre): static
    {
        if ($this->offres->removeElement($offre)) {
            // set the owning side to null (unless already changed)
            if ($offre->getSocietes() === $this) {
                $offre->setSocietes(null);
            }
        }

        return $this;
    }


}