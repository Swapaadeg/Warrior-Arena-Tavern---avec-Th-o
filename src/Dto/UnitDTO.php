<?php
declare(strict_types=1);

namespace App\Dto;

final class UnitDTO
{
    public readonly string $id;
    public readonly string $name;
    public readonly string $role;
    public readonly int $hp;
    public readonly int $maxHp;
    public readonly int $power;
    public readonly int $defense;
    public readonly bool $alive;

    /**
     * Accept numeric scalars for robustness and cast internally.
     * @param int|string $hp
     * @param int|string $maxHp
     * @param int|string $power
     * @param int|string $defense
     */
    public function __construct(
        string $id,
        string $name,
        string $role,
        int|string $hp,
        int|string $maxHp,
        int|string $power,
        int|string $defense,
        bool $alive = true,
    ) {
        $hpI = (int) $hp;
        $maxHpI = (int) $maxHp;
        $powerI = (int) $power;
        $defenseI = (int) $defense;

    // Normalize invalid inputs instead of throwing
    if ($maxHpI < 1) { $maxHpI = 1; }
    if ($hpI < 0) { $hpI = 0; }
    if ($hpI > $maxHpI) { $hpI = $maxHpI; }

        $this->id = $id;
        $this->name = $name;
        $this->role = $role;
        $this->hp = $hpI;
        $this->maxHp = $maxHpI;
        $this->power = $powerI;
        $this->defense = $defenseI;
        $this->alive = $alive;
    }

    public function withHp(int $hp, bool $alive): self
    {
        return new self($this->id, $this->name, $this->role, $hp, $this->maxHp, $this->power, $this->defense, $alive);
    }
}
