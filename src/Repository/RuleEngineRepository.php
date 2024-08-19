<?php

namespace App\Repository;

use App\Entity\RuleEngine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RuleEngine>
 *
 * @method RuleEngine|null find($id, $lockMode = null, $lockVersion = null)
 * @method RuleEngine|null findOneBy(array $criteria, array $orderBy = null)
 * @method RuleEngine[]    findAll()
 * @method RuleEngine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RuleEngineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RuleEngine::class);
    }

//    /**
//     * @return RuleEngine[] Returns an array of RuleEngine objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?RuleEngine
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
