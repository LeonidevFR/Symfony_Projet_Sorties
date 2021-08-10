<?php

namespace App\Repository;

use App\Entity\Campus;
use App\Entity\Outings;
use App\Entity\Status;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;
use function Symfony\Component\DependencyInjection\Loader\Configurator\expr;

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
    public function findByParameter(User $user,$campus, $author , $memberTrue, $memberFalse) {
        $queryBuilder = $this->createQueryBuilder('o');
        if($author) {
            $queryBuilder->where('o.author = :author')
                        ->setParameter('author', $user);
        }
        if($memberTrue) {
            $queryBuilder
                ->where($queryBuilder->expr()->isMemberOf(':member','o.members'))
                ->setParameter(':member',$user);
        }
        if($memberFalse) {
            $queryBuilder
                ->where($queryBuilder->expr()->isMemberOf(':member','o.members'))
                ->setParameter(':member',$user == false);
        }

        $result = $queryBuilder->getQuery()->getResult();
        return $result;
    }
}
