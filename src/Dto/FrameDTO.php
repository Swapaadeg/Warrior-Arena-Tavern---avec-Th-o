<?php
declare(strict_types=1);

namespace App\Dto;

final class FrameDTO
{
    /** @param ActionDTO[] $actions @param array<string,int> $hpByUnit */
    public function __construct(
        public readonly int $tick,
        public readonly array $actions,
        public readonly array $hpByUnit,
        public readonly array $events,
    ) {}
}
