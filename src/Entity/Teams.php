<?php

namespace App\Entity;

use App\Repository\TeamsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeamsRepository::class)]
class Teams
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'team', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * @var Collection<int, Characters>
     */
    #[ORM\ManyToMany(targetEntity: Characters::class, inversedBy: 'teams')]
    private Collection $characters;

    #[ORM\Column]
    private ?int $totalPower = null;

    /**
     * @var Collection<int, QueueTicket>
     */
    #[ORM\OneToMany(targetEntity: QueueTicket::class, mappedBy: 'team')]
    private Collection $queueTickets;

    public function __construct()
    {
        $this->characters = new ArrayCollection();
        $this->queueTickets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Characters>
     */
    public function getCharacters(): Collection
    {
        return $this->characters;
    }

    public function addCharacter(Characters $character): static
    {
        if (!$this->characters->contains($character)) {
            $this->characters->add($character);
        }

        return $this;
    }

    public function removeCharacter(Characters $character): static
    {
        $this->characters->removeElement($character);

        return $this;
    }

    public function getTotalPower(): ?int
    {
        return $this->totalPower;
    }

    public function setTotalPower(int $totalPower): static
    {
        $this->totalPower = $totalPower;

        return $this;
    }

    /**
     * @return Collection<int, QueueTicket>
     */
    public function getQueueTickets(): Collection
    {
        return $this->queueTickets;
    }

    public function addQueueTicket(QueueTicket $queueTicket): static
    {
        if (!$this->queueTickets->contains($queueTicket)) {
            $this->queueTickets->add($queueTicket);
            $queueTicket->setTeam($this);
        }

        return $this;
    }

    public function removeQueueTicket(QueueTicket $queueTicket): static
    {
        if ($this->queueTickets->removeElement($queueTicket)) {
            // set the owning side to null (unless already changed)
            if ($queueTicket->getTeam() === $this) {
                $queueTicket->setTeam(null);
            }
        }

        return $this;
    }
}
