<?php

namespace App\Repository;

use App\Entity\QuizzResult;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QuizzResult>
 */
class QuizzResultRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuizzResult::class);
    }
    public function getAverageScoreByMatiere(int $userId): array
{
    return $this->getEntityManager()
        ->createQuery(
            'SELECT q.matiere, AVG(qr.score) as averageScore
            FROM App\Entity\QuizzResult qr
            JOIN qr.quizz q
            WHERE qr.user = :userId
            GROUP BY q.matiere'
        )
        ->setParameter('userId', $userId)
        ->getResult();
}


    //    /**
    //     * @return QuizzResult[] Returns an array of QuizzResult objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('q')
    //            ->andWhere('q.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('q.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?QuizzResult
    //    {
    //        return $this->createQueryBuilder('q')
    //            ->andWhere('q.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
