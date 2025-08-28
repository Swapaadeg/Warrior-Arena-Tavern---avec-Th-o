<?php

// Test simple du générateur déterministe
class TestCombatService {
    private int $seedState;

    private function deterministicRand(int $min, int $max): int
    {
        $this->seedState = ($this->seedState * 1103515245 + 12345) & 0x7fffffff;
        $range = $max - $min + 1;
        return $min + ($this->seedState % $range);
    }

    private function deterministicShuffle(array &$array): void
    {
        $count = count($array);
        for ($i = $count - 1; $i > 0; $i--) {
            $j = $this->deterministicRand(0, $i);
            $temp = $array[$i];
            $array[$i] = $array[$j];
            $array[$j] = $temp;
        }
    }

    public function testDeterminism($seed) {
        $this->seedState = $seed;

        $results = [];

        // Test random numbers
        for ($i = 0; $i < 10; $i++) {
            $results[] = $this->deterministicRand(1, 100);
        }

        // Test shuffle
        $array = [1, 2, 3, 4, 5];
        $this->deterministicShuffle($array);
        $results[] = implode(',', $array);

        return $results;
    }
}

$tester = new TestCombatService();
$seed = 123456;

echo "Test de déterminisme avec le seed $seed:\n\n";

// Premier test
$results1 = $tester->testDeterminism($seed);
echo "Premier test:\n";
echo "Nombres aléatoires: " . implode(', ', array_slice($results1, 0, 10)) . "\n";
echo "Tableau mélangé: " . $results1[10] . "\n\n";

// Deuxième test avec le même seed
$results2 = $tester->testDeterminism($seed);
echo "Deuxième test (même seed):\n";
echo "Nombres aléatoires: " . implode(', ', array_slice($results2, 0, 10)) . "\n";
echo "Tableau mélangé: " . $results2[10] . "\n\n";

// Vérification
$identical = ($results1 === $results2);
echo "Les résultats sont " . ($identical ? "IDENTIQUES ✅" : "DIFFÉRENTS ❌") . "\n\n";

// Test avec un seed différent
$seed2 = 654321;
$results3 = $tester->testDeterminism($seed2);
echo "Troisième test avec seed différent ($seed2):\n";
echo "Nombres aléatoires: " . implode(', ', array_slice($results3, 0, 10)) . "\n";
echo "Tableau mélangé: " . $results3[10] . "\n\n";

$different = ($results1 !== $results3);
echo "Les résultats avec seed différent sont " . ($different ? "DIFFÉRENTS ✅" : "IDENTIQUES ❌") . "\n";
