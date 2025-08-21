<?php
declare(strict_types=1);

namespace App\Dto;

final class BattleSetupDTO
{
    public function __construct(
        public readonly TeamDTO $left,
        public readonly TeamDTO $right,
        public readonly int $seed = 42,
    ) {}
}
