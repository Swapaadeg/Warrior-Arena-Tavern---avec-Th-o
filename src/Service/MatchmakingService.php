<?php
namespace App\Service;

use App\Entity\User;
use App\Entity\Teams;
use App\Entity\GameMatch;
use App\Repository\UserRepository;
use App\Repository\MatchRepository;
use App\Repository\TeamsRepository;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Lock\LockFactory;
use Doctrine\ORM\EntityManagerInterface;

// src/Service/MatchmakingService.php (essential methods)
class MatchmakingService
{
    public function __construct(
        private EntityManagerInterface $em,
        private MatchRepository $repo,
        private UserRepository $users
    ) {}

    /** Try to pair or return "searching". */
    public function joinQueue(User $me, int $myTeamId): array
    {
        // 1) check existing active match
        if ($m = $this->repo->findActiveForUser($me)) {
            if ($m->getState() === GameMatch::STATE_MATCHED && $opp = $this->opponentOf($m, $me)) {
                return [
                    'matched'=>true,
                    'matchId'=>$m->getId(),
                    'opponent'=>['id'=>$opp->getId(),'username'=>$opp->getUsername()],
                ];
            }
            return ['matched'=>false];
        }

        // 2) try to consume someone else's SEARCHING row
        $this->em->beginTransaction();
        try {
            $waiting = $this->repo->lockFirstWaiting($me); // SELECT … FOR UPDATE SKIP LOCKED
            if ($waiting) {
                $waiting->setB($me);
                $waiting->setState(GameMatch::STATE_MATCHED);
                $this->em->flush();
                $this->em->commit();

                $opp = $waiting->getA();
                return [
                    'matched'=>true,
                    'matchId'=>$waiting->getId(),
                    'opponent'=>['id'=>$opp->getId(),'username'=>$opp->getUsername()],
                ];
            }

            // 3) create my SEARCHING row
            $m = new GameMatch();
            $m->setA($me);
            $m->setState(GameMatch::STATE_SEARCHING);
            $m->setCreatedAt(new \DateTimeImmutable());
            $m->setUpdatedAt(new \DateTime());
            $this->em->persist($m);
            $this->em->flush();
            $this->em->commit();

            return ['matched'=>false];
        } catch (\Throwable $e) {
            $this->em->rollback();
            return ['matched'=>false];
        }
    }

    public function cancel(User $me): void
    {
        if ($m = $this->repo->findActiveForUser($me, true/*lock*/)) {
            $m->setState(GameMatch::STATE_CANCELLED);
            $this->em->flush();
        }
    }

    public function markReady(User $me, int $matchId): void
    {
        $m = $this->repo->lockByIdForUser($matchId, $me);
        if (!$m || $m->getState() !== GameMatch::STATE_MATCHED) return;

        $me === $m->getA() ? $m->setAReady(true) : $m->setBReady(true);
        $m->setUpdatedAt(new \DateTime());
        $this->em->flush();
    }

    /** First click only sets your launch flag. Game starts only when both launch. */
    public function clickLaunch(User $me, int $matchId): array
    {
        $m = $this->repo->lockByIdForUser($matchId, $me, true); // SELECT FOR UPDATE
        if (!$m || $m->getState() !== GameMatch::STATE_MATCHED) return ['success'=>false];

        // Must be both ready first
        if (!($m->isAReady() && $m->isBReady())) return ['success'=>false, 'reason'=>'not_ready'];

        // Set your click
        $me === $m->getA() ? $m->setALaunch(true) : $m->setBLaunch(true);

        // If both clicked, launch
        if ($m->isALaunch() && $m->isBLaunch()) {
            $m->setState(GameMatch::STATE_LAUNCHED);
            $this->em->flush();
            $opp = $this->opponentOf($m, $me);
            return ['success'=>true, 'state'=>'launched', 'opponentId'=>$opp?->getId()];
        }

        $this->em->flush();
        return ['success'=>true, 'state'=>'waiting_other_click'];
    }

    public function snapshot(User $me, int $matchId): array
    {
        $m = $this->repo->findForUser($matchId, $me);
        if (!$m) return ['success'=>false];

        $youA = $me === $m->getA();
        $opp  = $youA ? $m->getB() : $m->getA();

        return [
            'success'=>true,
            'state'=>$m->getState(),
            'youReady'=> $youA ? $m->isAReady() : $m->isBReady(),
            'opponentReady'=> $youA ? $m->isBReady() : $m->isAReady(),
            'youClickedLaunch'=> $youA ? $m->isALaunch() : $m->isBLaunch(),
            'opponentClickedLaunch'=> $youA ? $m->isBLaunch() : $m->isALaunch(),
            'opponentId'=> $opp?->getId(),
        ];
    }

    private function opponentOf(GameMatch $m, User $me): ?User {
        return $me === $m->getA() ? $m->getB() : $m->getA();
    }
}
