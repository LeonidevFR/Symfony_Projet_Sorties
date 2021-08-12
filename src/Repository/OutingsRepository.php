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
    public function findByParameters($self,
                                    $campus,
                                    $contains,
                                    $dateDebut,
                                    $dateFin,
                                    $isAuthor,
                                    $isRegistered,
                                    $isUnregistered,
                                    $isFinished)
    {
        $qb = $this->createQueryBuilder('o')
            ->leftJoin('o.members', 'm')
            ->leftJoin('o.campus', 'c')
            ->leftJoin('o.status', 's')
            ->orderBy('o.id','DESC');
        if(null != $campus){
            $qb->andWhere('c = :campus')
                ->setParameter('campus', $campus);
        }
        if(null != $contains){
            $qb->andWhere('o.nameOuting LIKE :contains')
                ->setParameter('contains', '%'.$contains.'%');
        }
        if($dateDebut != $dateFin){
            $qb->andWhere('o.dateHourOuting >= :dateDebut')
                ->andWhere('o.dateHourOuting <= :dateFin')
                ->setParameters([
                    'dateDebut' => $dateDebut,
                    'dateFin' => $dateFin
                ]);
        }
        if(null != $isAuthor)
            $qb->andWhere('o.author = :self')
                ->setParameter('self', $self);
        if(null != $isRegistered || null != $isUnregistered) {
            if(null != $isRegistered && null == $isUnregistered) {
                $qb->andWhere('m = :self')
                    ->setParameter('self', $self);
            } else if(null != $isUnregistered && null == $isRegistered){
                $qb->andWhere('m != :self')
                    ->orWhere('m IS NULL')
                    ->setParameter('self', $self);
            }
        }
        if(null != $isFinished)
            $qb->andWhere('s.wording = :term')
            ->setParameter('term', 'Passé');
        return $qb->getQuery()->getResult();
    }
}
