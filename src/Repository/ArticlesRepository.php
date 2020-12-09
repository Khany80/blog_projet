<?php

namespace App\Repository;

use App\Entity\Articles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
//use DoctrineExtensions\Query\Mysql\Rand;

/**
 * @method Articles|null find($id, $lockMode = null, $lockVersion = null)
 * @method Articles|null findOneBy(array $criteria, array $orderBy = null)
 * @method Articles[]    findAll()
 * @method Articles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticlesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Articles::class);
    }

    // /**
    //  * @return Articles[] Returns an array of Articles objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Articles
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function lastedArticle()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.created_at', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()[0];
    }

    public function randomArticle($ids)
    {
        $qb = $this->createQueryBuilder('a');

        return $qb
            ->where($qb->expr()->notIn('a.id', $ids))
            ->orderBy('RAND()')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()[0];
    }
}
