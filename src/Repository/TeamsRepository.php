<?php

namespace App\Repository;

use App\Entity\Teams;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Teams>
 */
final class TeamsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Teams::class);
    }

    /** Returns a random opponent team not owned by $user. Optionally exclude a given team. */
    public function findRandomOpponentNotOwnedBy(User $user, ?Teams $exclude = null): ?Teams
    {
        // count candidates
        $qbCount = $this->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->join('t.user', 'u')
            ->andWhere('u != :u')
            ->setParameter('u', $user);

        if ($exclude) {
            $qbCount->andWhere('t != :ex')->setParameter('ex', $exclude);
        }

        $count = (int)$qbCount->getQuery()->getSingleScalarResult();
        if ($count === 0) {
            return null;
        }

        $offset = random_int(0, $count - 1);

        $qb = $this->createQueryBuilder('t')
            ->join('t.user', 'u')
            ->andWhere('u != :u')
            ->setParameter('u', $user)
            ->setFirstResult($offset)
            ->setMaxResults(1);

        if ($exclude) {
            $qb->andWhere('t != :ex')->setParameter('ex', $exclude);
        }

        return $qb->getQuery()->getOneOrNullResult();
    }
}
