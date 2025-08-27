<?php

namespace App\Repository;

use App\Entity\Weapons;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Weapons>
 */
class WeaponsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Weapons::class);
    }

    /**
     * Récupère un nombre spécifié d'armes aléatoires
     */
    public function findRandomWeapons(int $limit = 3): array
    {
        // Récupérer tous les IDs d'armes
        $allIds = $this->createQueryBuilder('w')
            ->select('w.id')
            ->getQuery()
            ->getArrayResult();
        
        if (empty($allIds)) {
            return [];
        }
        
        // Mélanger les IDs et prendre les premiers
        $ids = array_column($allIds, 'id');
        shuffle($ids);
        $randomIds = array_slice($ids, 0, $limit);
        
        // Récupérer les objets Weapons complets avec leurs relations
        return $this->createQueryBuilder('w')
            ->leftJoin('w.types', 't')
            ->addSelect('t')
            ->where('w.id IN (:ids)')
            ->setParameter('ids', $randomIds)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Weapons[] Returns an array of Weapons objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('w.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Weapons
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
