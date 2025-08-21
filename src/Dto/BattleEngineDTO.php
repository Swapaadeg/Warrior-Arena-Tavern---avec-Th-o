<?php
declare(strict_types=1);

namespace App\Dto;

/**
 * A minimal, self-contained battle engine placed in the DTO namespace so
 * the battle logic lives alongside the data shapes. It returns a
 * BattleResultDTO (frames + winner) and uses simple string action kinds.
 */
final class BattleEngineDTO
{
    public static function run(BattleSetupDTO $setup): BattleResultDTO
    {
        $left = $setup->left;
        $right = $setup->right;

        // clone units state
        $units = [];
        foreach ([$left, $right] as $team) {
            foreach ($team->units as $u) $units[$u->id] = $u;
        }
        $frames = [];
        $tick = 0;

        // up to 10 ticks or until a side is down
        while ($tick < 10) {
            $actions = [];
            $events = [];

            foreach ($left->units as $L) {
                $target = self::thisTarget($right->units, $units);
                if (!$target) { break; }
                $dmg = max(1, $L->power - (int)floor($units[$target->id]->defense * 0.5));
                $newHp = max(0, $units[$target->id]->hp - $dmg);
                $alive = $newHp > 0;
                $units[$target->id] = $units[$target->id]->withHp($newHp, $alive);
                $actions[] = new ActionDTO($L->id, 'ATTACK', $target->id, $dmg);
                $events[] = $L->name . ' attacked ' . $target->name . ' for ' . $dmg;
            }

            foreach ($right->units as $R) {
                $target = self::thisTarget($left->units, $units);
                if (!$target) { break; }
                $dmg = max(1, $R->power - (int)floor($units[$target->id]->defense * 0.5));
                $newHp = max(0, $units[$target->id]->hp - $dmg);
                $alive = $newHp > 0;
                $units[$target->id] = $units[$target->id]->withHp($newHp, $alive);
                $actions[] = new ActionDTO($R->id, 'ATTACK', $target->id, $dmg);
                $events[] = $R->name . ' attacked ' . $target->name . ' for ' . $dmg;
            }

            $hpByUnit = [];
            foreach ($units as $id => $u) $hpByUnit[$id] = $u->hp;
            $frames[] = new FrameDTO($tick, $actions, $hpByUnit, $events);

            $leftAlive = array_filter($left->units, fn(UnitDTO $u) => $units[$u->id]->alive);
            $rightAlive = array_filter($right->units, fn(UnitDTO $u) => $units[$u->id]->alive);
            if (!$leftAlive || !$rightAlive) break;
            $tick++;
        }

        $winner = 'left';
        $leftCount = 0; $rightCount = 0;
        foreach ($left->units as $u) if ($units[$u->id]->alive) $leftCount++;
        foreach ($right->units as $u) if ($units[$u->id]->alive) $rightCount++;
        if ($rightCount > $leftCount) $winner = 'right';

        return new BattleResultDTO($winner, $tick + 1, $frames);
    }

    /** @return UnitDTO|null */
    private static function thisTarget(array $candidates, array $units): ?UnitDTO
    {
        $alive = array_filter($candidates, fn(UnitDTO $u) => $units[$u->id]->alive);
        if (!$alive) return null;
        // pick the lowest HP target
        usort($alive, fn(UnitDTO $a, UnitDTO $b) => $units[$a->id]->hp <=> $units[$b->id]->hp);
        return $alive[0] ?? null;
    }
}
