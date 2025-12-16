<?php

namespace App\Entity;

use App\Entity\Skills;
use App\Entity\Candidatures;
use App\Entity\Societes;
use Cocur\Slugify\Slugify;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\OffresRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: OffresRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity('slug', message: "Cette valeur est déjà utilisée.")]
#[UniqueEntity('refMission', message: "Cette valeur est déjà utilisée.")]
class Offres
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank( message: "Ce champ est obligatoire.",)]
    private string $nom  = '' ;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank( message: "Ce champ est obligatoire.",)]
    private string $description  = '' ;

    #[ORM\Column(length: 100)]
    #[Assert\Length(min: 5)]
    #[Assert\Regex("/^[a-z0-9]+(?:-[a-z0-9]+)*$/", message: "Invalid Slug")]
    private string $slug = '';

    #[ORM\Column(nullable: true)]
    #[Assert\Positive(message: "La valeur saisie n'est pas valide.",)]
    private ?int $tarif = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Positive(message: "La valeur saisie n'est pas valide.",)]
    private ?int $duree = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank( message: "Ce champ est obligatoire.",)]
    private string $lieuMission = '';

    #[ORM\Column]
    private ?bool $isActive = false;

    #[ORM\Column]
    private ?bool $isArchive = false;

    #[ORM\Column(nullable: true)]
    private ?int $experience = null;

    #[ORM\Column(type: Types::TEXT)]
    private string $profil = " ";

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $contraintes = null;

    #[ORM\Column(type: Types::TEXT, length: 100, nullable: true)]
    private ?string $refMission = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Assert\NotNull()]
    private ?DateTimeImmutable $startDateAT = null;

    #[ORM\ManyToOne(targetEntity: Societes::class, inversedBy: 'offres')]
    #[ORM\JoinColumn(nullable: false)]
    private  $societes;

    #[ORM\Column(nullable: true)]
    private ?int $nbCandidatures = null;

    /**
     * @var Collection<int, Candidatures>
     */
    #[ORM\OneToMany(targetEntity: Candidatures::class, mappedBy: 'offres')]
    private Collection $candidatures;

    /**
     * @var Collection<int, Skills>
     */
    #[ORM\ManyToMany(targetEntity: Skills::class, inversedBy: 'offres')]
    private Collection $skills;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->skills = new ArrayCollection();
        $this->startDateAT = new \DateTimeImmutable();
        $this->candidatures = new ArrayCollection();
    }

    #[ORM\PrePersist()]
    public function prePresist(){
        $nom = $this->nom.uniqid();
        $this->slug = (new Slugify())->slugify(strtolower($nom)) ;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = (new Slugify())->slugify(strtolower($slug)) ;

        return $this;
    }

    public function getTarif(): ?int
    {
        return $this->tarif;
    }

    public function setTarif(?int $tarif): static
    {
        $this->tarif = $tarif;

        return $this;
    }

    public function getStartDateAT(): ?\DateTimeImmutable
    {
        return $this->startDateAT;
    }

    public function setStartDateAT(\DateTimeImmutable $startDateAT): static
    {
        $this->startDateAT = $startDateAT;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): static
    {
        $this->duree = $duree;

        return $this;
    }

    public function getLieuMission(): ?string
    {
        return $this->lieuMission;
    }

    public function setLieuMission(string $lieuMission): static
    {
        $this->lieuMission = $lieuMission;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function isArchive(): ?bool
    {
        return $this->isArchive;
    }

    public function setIsArchive(bool $isArchive): static
    {
        $this->isArchive = $isArchive;

        return $this;
    }

    public function getExperience(): ?int
    {
        return $this->experience;
    }

    public function setExperience(int $experience): static
    {
        $this->experience = $experience;

        return $this;
    }


    public function getProfil(): ?string
    {
        return $this->profil;
    }

    public function setProfil(?string $profil): static
    {
        $this->profil = $profil;

        return $this;
    }

    public function getContraintes(): ?string
    {
        return $this->contraintes;
    }

    public function setContraintes(?string $contraintes): static
    {
        $this->contraintes = $contraintes;

        return $this;
    }

    public function getRefMission(): ?string
    {
        return $this->refMission;
    }

    public function setRefMission(?string $refMission): static
    {
        $this->refMission = $refMission;

        return $this;
    }

    public function getSocietes(): ?Societes
    {
        return $this->societes;
    }

    public function setSocietes(?Societes $societes): static
    {
        $this->societes = $societes;

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
            $candidature->setOffres($this);
        }

        return $this;
    }

    public function removeCandidature(Candidatures $candidature): static
    {
        if ($this->candidatures->removeElement($candidature)) {
            // set the owning side to null (unless already changed)
            if ($candidature->getOffres() === $this) {
                $candidature->setOffres(null);
            }
        }

        return $this;
    }

    public function getNbCandidatures(): ?int
    {
        return $this->nbCandidatures;
    }

    public function setNbCandidatures(int $nbCandidatures): static
    {
        $this->nbCandidatures = $nbCandidatures;

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