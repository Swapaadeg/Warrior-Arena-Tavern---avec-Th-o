<?php
// src/Repository/MatchRepository.php
namespace App\Repository;

use App\Entity\GameMatch;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\LockMode;
use Doctrine\ORM\NonUniqueResultException;

/**
 * @extends ServiceEntityRepository<Match>
 */
class MatchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameMatch::class);
    }

    /**
     * A user can have at most one active match (searching or matched).
     */
    public function findActiveForUser(User $u, bool $lock = false): ?GameMatch
    {
        $qb = $this->createQueryBuilder('m')
            ->andWhere('m.state IN (:states)')
            ->setParameter('states', [GameMatch::STATE_SEARCHING, GameMatch::STATE_MATCHED])
            ->andWhere('(m.a = :u OR m.b = :u)')
            ->setParameter('u', $u)
            ->orderBy('m.createdAt', 'ASC')
            ->setMaxResults(1);

        $q = $qb->getQuery();
        if ($lock) {
            $q->setLockMode(LockMode::PESSIMISTIC_WRITE);
        }

        try {
            return $q->getOneOrNullResult();
        } catch (NonUniqueResultException) {
            return null;
        }
    }

    /**
     * Lock and return the oldest waiting row not created by $exclude.
     * Uses pessimistic write to avoid races. Works on MySQL and SQLite.
     * If you run PostgreSQL and want SKIP LOCKED, switch to native SQL.
     */
    public function lockFirstWaiting(User $exclude): ?GameMatch
    {
        $em = $this->getEntityManager();

        $qb = $this->createQueryBuilder('m')
            ->andWhere('m.state = :s')->setParameter('s', GameMatch::STATE_SEARCHING)
            ->andWhere('m.a != :u')->setParameter('u', $exclude)
            ->orderBy('m.createdAt', 'ASC')
            ->setMaxResults(1);

        $q = $qb->getQuery();
        $q->setLockMode(LockMode::PESSIMISTIC_WRITE);

        try {
            /** @var GameMatch|null $row */
            $row = $q->getOneOrNullResult();
        } catch (NonUniqueResultException) {
            $row = null;
        }

        return $row;
    }

    /**
     * Lock a match by id only if the user participates.
     */
    public function lockByIdForUser(int $id, User $u, bool $write = false): ?GameMatch
    {
        $em = $this->getEntityManager();

        $qb = $this->createQueryBuilder('m')
            ->andWhere('m.id = :id')->setParameter('id', $id)
            ->andWhere('(m.a = :u OR m.b = :u)')->setParameter('u', $u)
            ->setMaxResults(1);

        $q = $qb->getQuery();
        $q->setLockMode($write ? LockMode::PESSIMISTIC_WRITE : LockMode::PESSIMISTIC_READ);

        try {
            /** @var Match|null $m */
            $m = $q->getOneOrNullResult();
        } catch (NonUniqueResultException) {
            $m = null;
        }

        return $m;
    }

    /**
     * Read snapshot by id for a participant, no lock.
     */
    public function findForUser(int $id, User $u): ?GameMatch
    {
        $qb = $this->createQueryBuilder('m')
            ->andWhere('m.id = :id')->setParameter('id', $id)
            ->andWhere('(m.a = :u OR m.b = :u)')->setParameter('u', $u)
            ->setMaxResults(1);

        try {
            return $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException) {
            return null;
        }
    }
}
