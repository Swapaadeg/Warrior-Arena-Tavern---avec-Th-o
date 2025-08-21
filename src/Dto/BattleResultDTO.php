<?php
declare(strict_types=1);

namespace App\Dto;

final class BattleResultDTO
{
    /** @param FrameDTO[] $frames */
    public function __construct(
        public readonly string $winnerTeamId,
        public readonly int $ticks,
        public readonly array $frames,
    ) {}
}
