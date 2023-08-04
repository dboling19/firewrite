<?php

namespace App\Repository;

use App\Entity\AppEntityArticleTags;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AppEntityArticleTags>
 *
 * @method AppEntityArticleTags|null find($id, $lockMode = null, $lockVersion = null)
 * @method AppEntityArticleTags|null findOneBy(array $criteria, array $orderBy = null)
 * @method AppEntityArticleTags[]    findAll()
 * @method AppEntityArticleTags[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppEntityArticleTagsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppEntityArticleTags::class);
    }

//    /**
//     * @return AppEntityArticleTags[] Returns an array of AppEntityArticleTags objects
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

//    public function findOneBySomeField($value): ?AppEntityArticleTags
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
