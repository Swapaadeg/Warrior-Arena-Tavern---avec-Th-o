<?php
use App\Dto\UnitDTO;
use App\Dto\TeamDTO;
use App\Dto\ActionDTO;

require_once __DIR__ . '/bootstrap.php';

// create 5 simple units per side
$left = [];
$right = [];
for ($i = 0; $i < 5; $i++) {
    $left[] = new UnitDTO('L'.$i, 'L'.$i, 'soldier', 10, 10, 3, 1);
    $right[] = new UnitDTO('R'.$i, 'R'.$i, 'soldier', 10, 10, 2, 1);
}

$teamL = new TeamDTO('left', $left);
$teamR = new TeamDTO('right', $right);

$res = ActionDTO::simulateBattle($teamL, $teamR, 10);

echo "Winner: {$res->winnerTeamId}\n";
echo "Ticks: {$res->ticks}\n";
echo "Frames: " . count($res->frames) . "\n";
