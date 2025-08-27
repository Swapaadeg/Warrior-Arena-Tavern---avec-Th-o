<?php
// src/Entity/Match.php
namespace App\Entity;

use App\Repository\MatchRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MatchRepository::class)]
#[ORM\Table(name: 'game_match')]
#[ORM\HasLifecycleCallbacks]
class GameMatch
{
    public const STATE_SEARCHING = 'searching';
    public const STATE_MATCHED   = 'matched';
    public const STATE_LAUNCHED  = 'launched';
    public const STATE_CANCELLED = 'cancelled';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?User $a = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?User $b = null;

    #[ORM\Column(type: 'string', length: 32)]
    private string $state = self::STATE_SEARCHING;

    #[ORM\Column(type: 'boolean')]
    private bool $aReady = false;

    #[ORM\Column(type: 'boolean')]
    private bool $bReady = false;

    #[ORM\Column(type: 'boolean')]
    private bool $aLaunch = false;

    #[ORM\Column(type: 'boolean')]
    private bool $bLaunch = false;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $updatedAt;

    public function __construct()
    {
        $now = new \DateTimeImmutable('now');
        $this->createdAt = $now;
        $this->updatedAt = \DateTime::createFromImmutable($now);
    }

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $now = new \DateTimeImmutable('now');
        $this->createdAt = $now;
        $this->updatedAt = \DateTime::createFromImmutable($now);
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new \DateTime('now');
    }

    // ——— Getters / setters ———

    public function getId(): ?int { return $this->id; }

    public function getA(): ?User { return $this->a; }
    public function setA(?User $user): self { $this->a = $user; return $this; }

    public function getB(): ?User { return $this->b; }
    public function setB(?User $user): self { $this->b = $user; return $this; }

    public function getState(): string { return $this->state; }
    public function setState(string $state): self { $this->state = $state; return $this; }

    public function isAReady(): bool { return $this->aReady; }
    public function setAReady(bool $ready): self { $this->aReady = $ready; return $this; }

    public function isBReady(): bool { return $this->bReady; }
    public function setBReady(bool $ready): self { $this->bReady = $ready; return $this; }

    public function isALaunch(): bool { return $this->aLaunch; }
    public function setALaunch(bool $clicked): self { $this->aLaunch = $clicked; return $this; }

    public function isBLaunch(): bool { return $this->bLaunch; }
    public function setBLaunch(bool $clicked): self { $this->bLaunch = $clicked; return $this; }

    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $at): self { $this->createdAt = $at; return $this; }

    public function getUpdatedAt(): \DateTime { return $this->updatedAt; }
    public function setUpdatedAt(\DateTime $at): self { $this->updatedAt = $at; return $this; }

    // Helpers

    public function bothReady(): bool
    {
        return $this->aReady && $this->bReady;
    }

    public function bothClickedLaunch(): bool
    {
        return $this->aLaunch && $this->bLaunch;
    }

    public function opponentOf(User $u): ?User
    {
        if ($this->a && $u->getId() === $this->a->getId()) return $this->b;
        if ($this->b && $u->getId() === $this->b->getId()) return $this->a;
        return null;
    }
}
