<?php

namespace App\Entity;

use App\Repository\SkillsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SkillsRepository::class)]
class Skills
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank( message: "Ce champ est obligatoire.",)]
    private ?string $nom = null;

    #[ORM\Column(length: 60)]
    private ?string $slug = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'skills')]
    private ?self $parent = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parent')]
    private Collection $skills;

    /**
     * @var Collection<int, Offres>
     */
    #[ORM\ManyToMany(targetEntity: Offres::class, mappedBy: 'skills')]
    private Collection $offres;

    /**
     * @var Collection<int, Clients>
     */
    #[ORM\ManyToMany(targetEntity: Clients::class, mappedBy: 'skills')]
    private Collection $clients;


    public function __construct()
    {
        $this->skills = new ArrayCollection(); // parent_id
        $this->offres = new ArrayCollection();
        $this->clients = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getUsers(): ?users
    {
        return $this->users;
    }

    public function setUsers(?users $users): static
    {
        $this->users = $users;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    // Grace à cette fonction on va récupèrer les catégories enfants d'un parent
    /**
     * @return Collection<int, self>
     */
    public function getSkills(): Collection
    {
        return $this->skills;
    }

    public function addSkill(self $skill): static
    {
        if (!$this->skills->contains($skill)) {
            $this->skills->add($skill);
            $skill->setParent($this);
        }

        return $this;
    }

    public function removeSkill(self $skill): static
    {
        if ($this->skills->removeElement($skill)) {
            // set the owning side to null (unless already changed)
            if ($skill->getParent() === $this) {
                $skill->setParent(null);
            }
        }

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
            $offre->addSkill($this);
        }

        return $this;
    }

    public function removeOffre(Offres $offre): static
    {
        if ($this->offres->removeElement($offre)) {
            $offre->removeSkill($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Clients>
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Clients $client): static
    {
        if (!$this->clients->contains($client)) {
            $this->clients->add($client);
            $client->addSkill($this);
        }

        return $this;
    }

    public function removeClient(Clients $client): static
    {
        if ($this->clients->removeElement($client)) {
            $client->removeSkill($this);
        }

        return $this;
    }

}