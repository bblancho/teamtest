<?php

namespace App\Entity;

use App\Entity\Users;
use App\Entity\Skills;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClientsRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ClientsRepository::class)]
#[Vich\Uploadable]
#[UniqueEntity('siren', message: "Cette valeur est déjà utilisée.")]
class Clients extends Users
{
    #[ORM\Column(nullable: true)]
    #[Assert\Positive(message: "La valeur saisie n'est pas valide.",)]
    private ?int $tjm = null;

    #[ORM\Column(nullable: true)]
    private ?bool $dispo = null;

    #[ORM\Column(nullable: true)]
    #[Assert\File(
        maxSize: '2048k',
        extensions: ['pdf'],
        extensionsMessage: 'Veuillez télécharger un fichier PDF valide.',
        maxSizeMessage: "Le fichier doit faire au maximum ({{ size }} {{ suffix }}).",
    )]
    private ?File $cvFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cvName = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dateDispoAt = null;
    
    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(
        message: "Le champ siren/siret est obligatoire.",
    )] 
    // #[Assert\Regex(
    //     pattern: '/\d/',
    //     message: 'Le numéro de siret doit contenir que des chiffres.',
    // )]
    #[Assert\Type(
        type: 'int',
        message: 'Le numéro de siret doit contenir que des chiffres.',
    )]
    #[Assert\Length( 
        min: 9,
        max: 14,
        minMessage: "Le numéro de siren/siert doit faire {{ limit }} ou 14 caractères.",
        maxMessage: "Le numéro de siren/siert doit faire {{ limit }} ou 9 caractères."
    )]
    private string $siren ;

    /**
     * @var Collection<int, Candidatures>
     */
    #[ORM\OneToMany(targetEntity: Candidatures::class, mappedBy: 'clients')]
    private Collection $candidatures;

    /**
     * @var Collection<int, Skills>
     */
    #[ORM\ManyToMany(targetEntity: Skills::class, inversedBy: 'clients')]
    private Collection $skills;


    public function __construct()
    {
        $this->candidatures = new ArrayCollection();
        $this->skills = new ArrayCollection();
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

    /******* Les fichiers */
    public function setCvFile(?File $cvFile = null): self
    {
        $this->cvFile = $cvFile;

        return $this;
    }
    
    public function getCvFile(): File
    {
        return $this->cvFile;
    }
    /******* Fin fichiers */

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

    /**
     * @return Collection<int, Skills>
     */
    public function getSkills(): Collection
    {
        return $this->skills;
    }

    public function addSkill(Skills $skill): static
    {
        if (!$this->skills->contains($skill)) {
            $this->skills->add($skill);
        }

        return $this;
    }

    public function removeSkill(Skills $skill): static
    {
        $this->skills->removeElement($skill);

        return $this;
    }

    
}