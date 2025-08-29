<?php
// Simple CLI check to verify that the seed dictates battle outcome deterministically
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . '/vendor/autoload.php';

use App\Service\CombatService;
use App\Entity\User;
use App\Entity\Teams;
use App\Entity\Characters;
use App\Entity\Roles;

function makeChar(int $id, string $name, int $hp, int $power, int $defense, string $roleName): Characters {
    $r = (new Roles())->setName($roleName);
    $c = (new Characters())
        ->setName($name)
        ->setHP($hp)
        ->setPower($power)
        ->setDefense($defense)
        ->setRole($r);
    // set private id via reflection for stable IDs in logs
    $ref = new ReflectionClass($c);
    $prop = $ref->getProperty('id');
    $prop->setAccessible(true);
    $prop->setValue($c, $id);
    return $c;
}

function makeUserWithTeam(string $username, array $chars): User {
    $u = (new User())->setUsername($username)->setPassword('x');
    $t = new Teams();
    $t->setUser($u);
    $sum = 0;
    foreach ($chars as $ch) {
        $t->addCharacter($ch);
        $sum += (int)($ch->getPower() ?? 0);
    }
    $t->setTotalPower($sum);
    $u->setTeam($t);
    return $u;
}

// Build two small teams
$team1 = [
    makeChar(1, 'Warrior1', 100, 20, 10, 'Tank'),
    makeChar(2, 'Mage1',    80, 25,  5, 'DPS'),
];
$team2 = [
    makeChar(3, 'Warrior2',  90, 18, 12, 'Tank'),
    makeChar(4, 'Archer2',   70, 22,  8, 'DPS'),
];

$u1 = makeUserWithTeam('Alice', $team1);
$u2 = makeUserWithTeam('Bob',   $team2);

$svc = new CombatService();
$seed = 123456;

function summarize(array $res): array {
    return [
        'winner' => $res['result']['winnerTeamId'] ?? null,
        'ticks'  => $res['ticks'] ?? $res['result']['ticks'] ?? null,
        'first_actions' => array_map(function($a){
            return $a['kind'] . ':' . $a['actorId'] . '->' . $a['targetId'] . ':' . $a['amount'] . (!empty($a['critical']) ? 'C' : '');
        }, $res['frames'][0]['actions'] ?? []),
        'seed' => $res['setup']['seed'] ?? null,
    ];
}

$r1 = $svc->simulateBattle($u1, $u2, $seed);
$r2 = $svc->simulateBattle($u1, $u2, $seed);
$r3 = $svc->simulateBattle($u1, $u2, 654321);

$s1 = summarize($r1);
$s2 = summarize($r2);
$s3 = summarize($r3);

$identical = ($s1 == $s2);
$different = ($s1 != $s3);

echo "Seed determinism check\n";
echo "Seed used: {$s1['seed']}\n";
echo "Run1 winner: {$s1['winner']}, ticks: {$s1['ticks']}\n";
echo "Run2 winner: {$s2['winner']}, ticks: {$s2['ticks']}\n";
echo "First actions R1: " . implode(' | ', $s1['first_actions']) . "\n";
echo "First actions R2: " . implode(' | ', $s2['first_actions']) . "\n";
echo "Identical (same seed): " . ($identical ? 'YES' : 'NO') . "\n\n";

echo "Different seed: {$s3['seed']}\n";
echo "Run3 winner: {$s3['winner']}, ticks: {$s3['ticks']}\n";
echo "First actions R3: " . implode(' | ', $s3['first_actions']) . "\n";
echo "Different from R1 (different seed): " . ($different ? 'YES' : 'NO') . "\n";

