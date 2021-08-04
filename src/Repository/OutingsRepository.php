<?php

namespace App\Repository;

use App\Entity\Outings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Outings|null find($id, $lockMode = null, $lockVersion = null)
 * @method Outings|null findOneBy(array $criteria, array $orderBy = null)
 * @method Outings[]    findAll()
 * @method Outings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OutingsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Outings::class);
    }

    // /**
    //  * @return Outings[] Returns an array of Outings objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Outings
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
