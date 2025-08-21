<?php
declare(strict_types=1);

namespace App\Dto;

final class ActionDTO
{
    public function __construct(
        public readonly string $actorId,
        public readonly string $kind,
        public readonly ?string $targetId,
        public readonly int $amount,
    ) {}
}
