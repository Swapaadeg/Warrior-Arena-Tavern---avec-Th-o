<?php

require_once 'vendor/autoload.php';

use App\Service\CombatService;

// Test simple pour vérifier que le seeding fonctionne
$combatService = new CombatService();

// Simuler deux utilisateurs factices
class FakeUser {
    private $team;
    public function __construct($team) { $this->team = $team; }
    public function getTeam() { return $this->team; }
    public function getUsername() { return 'TestUser'; }
}

class FakeTeam {
    private $characters;
    public function __construct($characters) { $this->characters = $characters; }
    public function getCharacters() { return $this->characters; }
}

class FakeCharacter {
    private $id, $name, $hp, $power, $defense, $role;
    public function __construct($id, $name, $hp, $power, $defense, $role) {
        $this->id = $id; $this->name = $name; $this->hp = $hp;
        $this->power = $power; $this->defense = $defense; $this->role = $role;
    }
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getHP() { return $this->hp; }
    public function getPower() { return $this->power; }
    public function getDefense() { return $this->defense; }
    public function getRole() { return (object)['getName' => fn() => $this->role]; }
}

// Créer des équipes de test
$team1 = [
    new FakeCharacter(1, 'Warrior1', 100, 20, 10, 'Tank'),
    new FakeCharacter(2, 'Mage1', 80, 25, 5, 'DPS'),
];

$team2 = [
    new FakeCharacter(3, 'Warrior2', 90, 18, 12, 'Tank'),
    new FakeCharacter(4, 'Archer2', 70, 22, 8, 'DPS'),
];

$user1 = new FakeUser(new FakeTeam($team1));
$user2 = new FakeUser(new FakeTeam($team2));

// Test avec le même seed
$seed = 123456;

echo "Test de déterminisme avec le seed $seed:\n\n";

// Première bataille
$result1 = $combatService->simulateBattle($user1, $user2, $seed);
echo "Première bataille - Gagnant: " . $result1['result']['winnerTeamId'] . "\n";
echo "Actions du premier round:\n";
foreach ($result1['frames'][0]['actions'] ?? [] as $action) {
    echo "- " . $action['kind'] . " de " . $action['actorId'] . " vers " . $action['targetId'] . " pour " . $action['amount'] . "\n";
}

echo "\n";

// Deuxième bataille avec le même seed
$result2 = $combatService->simulateBattle($user1, $user2, $seed);
echo "Deuxième bataille - Gagnant: " . $result2['result']['winnerTeamId'] . "\n";
echo "Actions du premier round:\n";
foreach ($result2['frames'][0]['actions'] ?? [] as $action) {
    echo "- " . $action['kind'] . " de " . $action['actorId'] . " vers " . $action['targetId'] . " pour " . $action['amount'] . "\n";
}

echo "\n";

// Vérifier si les résultats sont identiques
$identical = ($result1['result']['winnerTeamId'] === $result2['result']['winnerTeamId']);
echo "Les batailles sont " . ($identical ? "IDENTIQUES ✅" : "DIFFÉRENTES ❌") . "\n";

// Test avec un seed différent
$seed2 = 654321;
$result3 = $combatService->simulateBattle($user1, $user2, $seed2);
echo "\nTroisième bataille avec seed différent ($seed2) - Gagnant: " . $result3['result']['winnerTeamId'] . "\n";

echo "\nSeed utilisé dans setup:\n";
echo "Bataille 1: " . $result1['setup']['seed'] . "\n";
echo "Bataille 2: " . $result2['setup']['seed'] . "\n";
echo "Bataille 3: " . $result3['setup']['seed'] . "\n";
