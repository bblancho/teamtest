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
#[Vich\Uploadable]
class Clients extends Users
{
    #[ORM\Column(nullable: true)]
    private ?int $tjm = null;

    #[ORM\Column(nullable: true)]
    private ?bool $dispo = null;

    #[Vich\UploadableField(mapping: 'clients', fileNameProperty: 'cvName')]
    #[Assert\File(
        mimeTypes:["image/jpeg", "image/jpg", "image/png"],
        mimeTypesMessage: "Ce type de document {{ type }} n'est pas accepté.",
        maxSize: "2M",
        maxSizeMessage:" La taille maximum acceptée est de ({{ size }} {{ suffix }}. ",
        extensions: [ 
            'png',
            'jpeg',
            'jpg',
        ],
    )]
    private ?File $cvFile = null;

    #[ORM\Column( length: 255, nullable: true)]
    private ?string $cvName = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dateDispoAt = null;
    
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()] 
    // #[Assert\Length(
    //     exactly: 9,
    //     exactMessage: "Le numéro de SIREN doit faire {{ limit }} caractères."
    // )]
    private string $siren = " ";

    /**
     * @var Collection<int, Candidatures>
     */
    #[ORM\OneToMany(targetEntity: Candidatures::class, mappedBy: 'clients')]
    private Collection $candidatures;

    public function __construct()
    {
        //parent::__construct();
        $this->candidatures = new ArrayCollection();
    }

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

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $cvFile
     */
    public function setCvFile(?File $cvFile = null): void
    {
        $this->cvFile = $cvFile;

        // if (null !== $cvFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
        //     $this->updatedAt = new \DateTimeImmutable();
        // }
    }

    public function getCvFile(): ?File
    {
        return $this->cvFile;
    }

    public function setCvName(?string $cvName): void
    {
        $this->cvName = $cvName;
    }

    public function getCvName(): ?string
    {
        return $this->cvName;
    }

    public function getSiren(): string
    {
        return $this->siren;
    }

    public function setSiren(string $siren): static
    {
        $this->siren = $siren;

        return $this;
    }

    /**
     * @return Collection<int, Candidatures>
     */
    public function getCandidatures(): Collection
    {
        return $this->candidatures;
    }

    public function addCandidature(Candidatures $candidature): static
    {
        if (!$this->candidatures->contains($candidature)) {
            $this->candidatures->add($candidature);
            $candidature->setClients($this);
        }

        return $this;
    }

    public function removeCandidature(Candidatures $candidature): static
    {
        if ($this->candidatures->removeElement($candidature)) {
            // set the owning side to null (unless already changed)
            if ($candidature->getClients() === $this) {
                $candidature->setClients(null);
            }
        }

        return $this;
    }

    
}