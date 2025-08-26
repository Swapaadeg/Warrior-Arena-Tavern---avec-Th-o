<?php

namespace App\Repository;

use App\Entity\Characters;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Characters>
 */
class CharactersRepository extends ServiceEntityRepository
{
    /**
     * Retourne tous les personnages triés par rôle : tanks, healers, dps
     */
    public function findAllOrderedByRole(): array
    {
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.role', 'r')
            ->addSelect('r')
            ->orderBy(
                "CASE
                    WHEN LOWER(r.name) = 'tank' THEN 1
                    WHEN LOWER(r.name) IN ('healer', 'heal') THEN 2
                    ELSE 3
                END"
            , 'ASC')
            ->addOrderBy('c.name', 'ASC');
        return $qb->getQuery()->getResult();
    }
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Characters::class);
    }

    //    /**
    //     * @return Characters[] Returns an array of Characters objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Characters
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
