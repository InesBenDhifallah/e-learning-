<?php

namespace App\Repository;



use App\Entity\Abonnement;

use App\Entity\Paiement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Abonnement>
 */
class PaiementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Paiement::class);
    }

    public function paiementParAbonnement(){
        $entityManager = $this->getEntityManager();

    $query = $entityManager->createQuery(
        'SELECT a.type AS abonnement, SUM(p.montant) AS total
         FROM App\Entity\Paiement p
         JOIN p.id_abonnement a
         GROUP BY a.type
         ORDER BY total DESC'
    );

    return $query->getResult();
        
    }
    public function totalPaiements(): float
{
    $entityManager = $this->getEntityManager();

    $query = $entityManager->createQuery(
        'SELECT SUM(p.montant) AS total FROM App\Entity\Paiement p'
    );

    return (float) $query->getSingleScalarResult();
}
public function nombrePaiementsParAbonnement()
{
    $entityManager = $this->getEntityManager();

    $query = $entityManager->createQuery(
        'SELECT a.type AS abonnement, COUNT(p.id) AS nombre_paiements
         FROM App\Entity\Paiement p
         JOIN p.id_abonnement a
         GROUP BY a.type
         ORDER BY nombre_paiements DESC'
    );

    return $query->getResult();
}



    //    /**
    //     * @return Abonnement[] Returns an array of Abonnement objects
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

    //    public function findOneBySomeField($value): ?Abonnement
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
