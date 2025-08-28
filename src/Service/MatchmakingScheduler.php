<?php

namespace App\Service;

use App\Message\ProcessMatchmakingMessage;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;

class MatchmakingScheduler
{
    private bool $isProcessing = false;
    private const SCHEDULE_CACHE_KEY = 'matchmaking_scheduled';
    private const PROCESSING_CACHE_KEY = 'matchmaking_processing';
    private const LAST_PROCESS_CACHE_KEY = 'last_matchmaking_process';

    public function __construct(
        private CacheItemPoolInterface $cache,
        private MatchmakingService $matchmakingService,
        private MessageBusInterface $messageBus,
        private LoggerInterface $logger
    ) {}

    public function scheduleProcessing(string $reason = 'manual'): void
    {
        // Éviter les doublons - vérifier si déjà programmé
        $scheduledItem = $this->cache->getItem(self::SCHEDULE_CACHE_KEY);
        if ($scheduledItem->isHit()) {
            return;
        }

        // Marquer comme programmé pour 15 secondes
        $scheduledItem->set(true)->expiresAfter(15);
        $this->cache->save($scheduledItem);

        // Dispatcher le message avec un délai de 5 secondes
        $this->messageBus->dispatch(
            new ProcessMatchmakingMessage(time(), $reason),
            [new DelayStamp(5000)] // 5 secondes de délai
        );
    }

    public function scheduleImmediateProcessing(string $reason = 'immediate'): void
    {
        $this->messageBus->dispatch(
            new ProcessMatchmakingMessage(time(), $reason)
        );
    }

    public function processNow(): array
    {
        // Éviter le traitement concurrent
        $processingItem = $this->cache->getItem(self::PROCESSING_CACHE_KEY);
        if ($processingItem->isHit()) {
            return [
                'message' => 'Already processing',
                'matches_created' => 0,
                'players_waiting' => 0
            ];
        }

        // Marquer comme en cours de traitement
        $processingItem->set(true)->expiresAfter(30); // 30 secondes max
        $this->cache->save($processingItem);

        try {
            $this->isProcessing = true;

            // Traitement principal
            $result = $this->matchmakingService->processQueue();

            // Enregistrer le timestamp du dernier traitement
            $lastProcessItem = $this->cache->getItem(self::LAST_PROCESS_CACHE_KEY);
            $lastProcessItem->set(time())->expiresAfter(3600); // 1 heure
            $this->cache->save($lastProcessItem);

            // Si il y a encore des joueurs en attente, reprogrammer
            if (($result['players_waiting'] ?? 0) > 0) {
                $this->scheduleProcessing('players_still_waiting');
            }

            return $result;

        } catch (\Exception $e) {
            $this->logger->error('Error during matchmaking processing', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'error' => $e->getMessage(),
                'matches_created' => 0,
                'players_waiting' => 0
            ];

        } finally {
            $this->isProcessing = false;
            // Libérer le verrou de traitement
            $this->cache->deleteItem(self::PROCESSING_CACHE_KEY);
        }
    }

    public function getStatus(): array
    {
        $lastProcessItem = $this->cache->getItem(self::LAST_PROCESS_CACHE_KEY);
        $scheduledItem = $this->cache->getItem(self::SCHEDULE_CACHE_KEY);
        $processingItem = $this->cache->getItem(self::PROCESSING_CACHE_KEY);

        return [
            'last_process' => $lastProcessItem->isHit() ? $lastProcessItem->get() : null,
            'is_scheduled' => $scheduledItem->isHit(),
            'is_processing' => $processingItem->isHit(),
            'queue_size' => $this->matchmakingService->getQueueSize()
        ];
    }
}