<?php

namespace App\Entity;

use App\Repository\CharactersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types as DoctrineTypes;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CharactersRepository::class)]
class Characters
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $HP = null;

    #[ORM\Column]
    private ?int $power = null;

    #[ORM\Column]
    private ?int $defense = null;

    #[ORM\Column(type: DoctrineTypes::TEXT)]
    private ?string $description = null;

    /**
     * @var Collection<int, Teams>
     */
    #[ORM\ManyToMany(targetEntity: Teams::class, mappedBy: 'characters')]
    private Collection $teams;

    /**
     * @var Collection<int, Roles>
     */
    #[ORM\OneToMany(targetEntity: Roles::class, mappedBy: 'perso')]
    private Collection $role;

    /**
     * @var Collection<int, Types>
     */
    #[ORM\OneToMany(targetEntity: Types::class, mappedBy: 'perso')]
    private Collection $type;

    public function __construct()
    {
        $this->teams = new ArrayCollection();
        $this->role = new ArrayCollection();
        $this->type = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getHP(): ?int
    {
        return $this->HP;
    }

    public function setHP(int $HP): static
    {
        $this->HP = $HP;

        return $this;
    }

    public function getPower(): ?int
    {
        return $this->power;
    }

    public function setPower(int $power): static
    {
        $this->power = $power;

        return $this;
    }

    public function getDefense(): ?int
    {
        return $this->defense;
    }

    public function setDefense(int $defense): static
    {
        $this->defense = $defense;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Teams>
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(Teams $team): static
    {
        if (!$this->teams->contains($team)) {
            $this->teams->add($team);
            $team->addCharacter($this);
        }

        return $this;
    }

    public function removeTeam(Teams $team): static
    {
        if ($this->teams->removeElement($team)) {
            $team->removeCharacter($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Roles>
     */
    public function getRole(): Collection
    {
        return $this->role;
    }

    public function addRole(Roles $role): static
    {
        if (!$this->role->contains($role)) {
            $this->role->add($role);
            $role->setPerso($this);
        }

        return $this;
    }

    public function removeRole(Roles $role): static
    {
        if ($this->role->removeElement($role)) {
            // set the owning side to null (unless already changed)
            if ($role->getPerso() === $this) {
                $role->setPerso(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Types>
     */
    public function getType(): Collection
    {
        return $this->type;
    }

    public function addType(Types $type): static
    {
        if (!$this->type->contains($type)) {
            $this->type->add($type);
            $type->setPerso($this);
        }

        return $this;
    }

    public function removeType(Types $type): static
    {
        if ($this->type->removeElement($type)) {
            // set the owning side to null (unless already changed)
            if ($type->getPerso() === $this) {
                $type->setPerso(null);
            }
        }

        return $this;
    }
}
