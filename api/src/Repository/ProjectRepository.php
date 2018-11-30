<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Entity\Status;

/**
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function filters($cancelled, $unpaid, $overdue, $fridaySaturday, $saturdaySunday, $clientId, $usersId)
    {
        $qb = $this->createQueryBuilder('p');

        if ($cancelled == false) {
            $qb->andWhere('p.status != :status_new')->setParameter('status_new', Status::STATUS_NEW)
                ->andWhere('p.status != :status_inwork')->setParameter('status_inwork', Status::STATUS_INWORK)
                ->andWhere('p.status != :status_done')->setParameter('status_done', Status::STATUS_DONE)
                ->andWhere('p.status != :status_paid')->setParameter('status_paid', Status::STATUS_PAID);
        }

        if ($unpaid == false) {
            $qb->andWhere('p.status != :status_paid')->setParameter('status_paid', Status::STATUS_PAID)
                ->orWhere('p.status != :status_cancelled')->setParameter('status_cancelled', Status::STATUS_CANCELLED)
                ->andWhere('p.startDate >= DATE_ADD(p.startDate,14, \'day\')');
        }

        if ($overdue == false) {
            $qb->andWhere('p.status != :status_paid')->setParameter('status_paid', Status::STATUS_PAID)
                ->orWhere('p.status != :status_cancelled')->setParameter('status_cancelled', Status::STATUS_CANCELLED)
                ->andWhere('p.startDate <= DATE_ADD(p.startDate,14, \'day\')');
        }

        if ($fridaySaturday == true && $saturdaySunday == false) {
            $qb->andWhere('p.startDate >= :today')->setParameter('today', date('Y-m-d H:i:s', strtotime('today UTC')))
                ->andWhere('DAYOFWEEK(p.startDate) >= 6')
                ->andWhere('DAYOFWEEK(p.startDate) <= 7');
        }

        if ($saturdaySunday == true && $fridaySaturday == false) {
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
//     * @return Project[] Returns an array of Project objects
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
    public function findOneBySomeField($value): ?Project
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
