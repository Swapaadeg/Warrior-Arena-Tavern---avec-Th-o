<?php
declare(strict_types=1);

namespace App\Dto;

final class TeamDTO
{
    /** @param UnitDTO[] $units */
    public function __construct(
        public readonly string $id,
        public readonly array $units,
    ) {
        if (count($units) !== 5) {
            throw new \InvalidArgumentException('A team must have exactly 5 units');
        }
    }
}
