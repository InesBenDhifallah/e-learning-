<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    //    /**
    //     * @return Article[] Returns an array of Article objects
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

    //    public function findOneBySomeField($value): ?Article
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findAllWithComments(): array
    {
        return $this->createQueryBuilder('a')
            ->select('a', 'c', 'u')
            ->leftJoin('a.comments', 'c')
            ->leftJoin('a.user', 'u')
            ->orderBy('a.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function searchByTerm(string $term, int $page = 1, int $limit = 10): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.title LIKE :term')
            ->orWhere('a.content LIKE :term')
            ->setParameter('term', '%' . $term . '%')
            ->orderBy('a.createdAt', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findRecent(int $limit): array
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function searchByTitle(?string $query): array
    {
        $qb = $this->createQueryBuilder('a')
            ->leftJoin('a.comments', 'c')
            ->addSelect('c')
            ->leftJoin('a.user', 'u')
            ->addSelect('u')
            ->orderBy('a.createdAt', 'DESC');

        if ($query) {
            $qb->where('a.title LIKE :query')
               ->orWhere('a.content LIKE :query')
               ->setParameter('query', '%' . $query . '%');
        }

        return $qb->getQuery()->getResult();
    }

    public function searchWithFilters(?string $query = '', ?string $category = '', int $page = 1, int $limit = 6)
    {
        $qb = $this->createQueryBuilder('a')
            ->select('a', 'u', 'c')
            ->leftJoin('a.user', 'u')
            ->leftJoin('a.comments', 'c')
            ->orderBy('a.createdAt', 'DESC');

        if ($query) {
            $qb->andWhere('a.title LIKE :query OR a.content LIKE :query')
               ->setParameter('query', '%' . $query . '%');
        }

        if ($category) {
            $qb->andWhere('a.category = :category')
               ->setParameter('category', $category);
        }

        return $qb->setFirstResult(($page - 1) * $limit)
                 ->setMaxResults($limit)
                 ->getQuery()
                 ->getResult();
    }

    public function countSearchResults(?string $query = '', ?string $category = ''): int
    {
        $qb = $this->createQueryBuilder('a')
            ->select('COUNT(a.id)');

        if ($query) {
            $qb->andWhere('a.title LIKE :query OR a.content LIKE :query')
               ->setParameter('query', '%' . $query . '%');
        }

        if ($category) {
            $qb->andWhere('a.category = :category')
               ->setParameter('category', $category);
        }

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    public function findPaginatedArticles(int $page = 1, int $limit = 6): array
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.createdAt', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findPaginatedArticlesWithUsers(int $page = 1, int $limit = 6): array
    {
        return $this->createQueryBuilder('a')
            ->select('a', 'u', 'c')
            ->leftJoin('a.user', 'u')
            ->leftJoin('a.comments', 'c')
            ->orderBy('a.createdAt', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findArticleWithUserAndComments(int $id): ?Article
    {
        return $this->createQueryBuilder('a')
            ->select('a', 'u', 'c', 'cu')
            ->leftJoin('a.user', 'u')
            ->leftJoin('a.comments', 'c')
            ->leftJoin('c.user', 'cu')
            ->where('a.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAllCategories(): array
    {
        return $this->createQueryBuilder('a')
            ->select('DISTINCT a.category')
            ->where('a.category IS NOT NULL')
            ->orderBy('a.category', 'ASC')
            ->getQuery()
            ->getSingleColumnResult();
    }

    public function findPaginated(int $page, int $limit): array
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.createdAt', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
