<?php

namespace App\Repository;

use App\Entity\Module;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Module>
 */
class ModuleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Module::class);
    }

    public function findModulesByTeacher(User $teacher): array
    {
        return $this->createQueryBuilder('m')
            ->innerJoin('m.users', 'u') // Assurez-vous que la relation est correcte
            ->where('u.id = :teacherId')
            ->andWhere('JSON_CONTAINS(u.roles, :role) = 1') // Vérifier si ROLE_TEACHER est bien défini
            ->setParameter('teacherId', $teacher->getId())
            ->setParameter('role', '"ROLE_TEACHER"') // Vérifier le rôle au format JSON
            ->getQuery()
            ->getResult();
    }
    


    //    /**
    //     * @return Module[] Returns an array of Module objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Module
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
