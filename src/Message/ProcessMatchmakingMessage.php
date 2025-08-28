<?php

namespace App\Message;

class ProcessMatchmakingMessage
{
    public function __construct(
        public readonly int $timestamp,
        public readonly ?string $triggerReason = null
    ) {}
}