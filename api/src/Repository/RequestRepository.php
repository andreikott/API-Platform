<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Request;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Request|null find($id, $lockMode = null, $lockVersion = null)
 * @method Request|null findOneBy(array $criteria, array $orderBy = null)
 * @method Request[]    findAll()
 * @method Request[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RequestRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Request::class);
    }

    public function filters($fridaySaturday, $saturdaySunday, $clientId, $usersId)
    {
        $qb = $this->createQueryBuilder('p');

        if (true === $fridaySaturday && false === $saturdaySunday) {
            $qb->andWhere('p.startDate >= :today')->setParameter('today', date('Y-m-d H:i:s', strtotime('today UTC')))
                ->andWhere('DAYOFWEEK(p.startDate) >= 6')
                ->andWhere('DAYOFWEEK(p.startDate) <= 7');
        }

        if (true === $saturdaySunday && false === $fridaySaturday) {
            $qb->andWhere('p.startDate >= :today')->setParameter('today', date('Y-m-d H:i:s', strtotime('today UTC')))
                ->andWhere('DAYOFWEEK(p.startDate) <> 2')
                ->andWhere('DAYOFWEEK(p.startDate) <> 3')
                ->andWhere('DAYOFWEEK(p.startDate) <> 4')
                ->andWhere('DAYOFWEEK(p.startDate) <> 5')
                ->andWhere('DAYOFWEEK(p.startDate) <> 6');
        }

        if ($clientId) {
            $qb->andWhere('p.client = :client')->setParameter('client', $clientId);
        }

        if ($usersId) {
            $qb->innerJoin('p.users', 'u')->andWhere($qb->expr()->in('u.id', $usersId));
        }

        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return Request[] Returns an array of Request objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Request
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
