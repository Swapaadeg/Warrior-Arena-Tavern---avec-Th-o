<?php

namespace App\MessageHandler;

use App\Message\ProcessMatchmakingMessage;
use App\Service\MatchmakingService;
use App\Service\MatchmakingScheduler;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ProcessMatchmakingHandler
{
    public function __construct(
        private MatchmakingService $matchmakingService,
        private MatchmakingScheduler $matchmakingScheduler,
        private LoggerInterface $logger
    ) {}

    public function __invoke(ProcessMatchmakingMessage $message): void
    {
        $this->logger->info('Processing matchmaking', [
            'timestamp' => $message->timestamp,
            'trigger' => $message->triggerReason
        ]);

        $result = $this->matchmakingScheduler->processNow();
        
        $this->logger->info('Matchmaking processed', $result);
    }
}