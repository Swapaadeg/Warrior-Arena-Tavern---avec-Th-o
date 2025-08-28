<?php

namespace App\Entity;

use App\Repository\WATMatchRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WATMatchRepository::class)]
class WATMatch
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Teams $teamA = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Teams $teamB = null;

    #[ORM\ManyToOne]
    private ?Teams $winner = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeamA(): ?Teams
    {
        return $this->teamA;
    }

    public function setTeamA(?Teams $teamA): static
    {
        $this->teamA = $teamA;

        return $this;
    }

    public function getTeamB(): ?Teams
    {
        return $this->teamB;
    }

    public function setTeamB(?Teams $teamB): static
    {
        $this->teamB = $teamB;

        return $this;
    }

    public function getWinner(): ?Teams
    {
        return $this->winner;
    }

    public function setWinner(?Teams $winner): static
    {
        $this->winner = $winner;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }
}
