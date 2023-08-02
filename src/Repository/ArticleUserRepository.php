<?php

namespace App\Repository;

use App\Entity\ArticleUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ArticleUser>
 *
 * @method ArticleUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArticleUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArticleUser[]    findAll()
 * @method ArticleUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArticleUser::class);
    }

//    /**
//     * @return ArticleUser[] Returns an array of ArticleUser objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ArticleUser
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
