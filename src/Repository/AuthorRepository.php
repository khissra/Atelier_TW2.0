<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
/**
 * @extends ServiceEntityRepository<Author>
 *
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    public function add(Author $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Author $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function listAuthorByEmail(): array
     {
        $qb = $this->createQueryBuilder('a');
        $qb->orderBy('a.email', 'ASC'); 
        return $qb->getQuery()->getResult();
    }

public function findAuthorsByBookCountRange($minBookCount, $maxBookCount)
{
    $qb = $this->createQueryBuilder('a')
        ->where('a.nbBooks >= :minBookCount')
        ->andWhere('a.nbBooks <= :maxBookCount')
        ->setParameter('minBookCount', $minBookCount)
        ->setParameter('maxBookCount', $maxBookCount);

    return $qb->getQuery()->getResult();
}
public function deleteAuthorsWithZeroBooks(EntityManagerInterface $entityManager)
{
    $qb = $entityManager->createQueryBuilder();
    
    $qb->delete('App\Entity\Author', 'a')
       ->where('a.nbBooks = 0');
    
    $query = $qb->getQuery();
    $query->execute();
}

//    /**
//     * @return Author[] Returns an array of Author objects
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

//    public function findOneBySomeField($value): ?Author
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
