<?php

namespace App\Command;

use App\Service\MatchmakingScheduler;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:matchmaking:process',
    description: 'Process the matchmaking queue to create matches',
)]
class ProcessMatchmakingCommand extends Command
{
    public function __construct(
        private MatchmakingScheduler $scheduler
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Processing Matchmaking Queue');

        $result = $this->scheduler->processNow();

        if (isset($result['error'])) {
            $io->error('Error: ' . $result['error']);
            return Command::FAILURE;
        }

        $io->success([
            'Matchmaking processing completed!',
            sprintf('Matches created: %d', $result['matches_created'] ?? 0),
            sprintf('Players still waiting: %d', $result['players_waiting'] ?? 0),
            sprintf('Processing time: %.3f seconds', $result['processing_time'] ?? 0),
        ]);

        return Command::SUCCESS;
    }
}
