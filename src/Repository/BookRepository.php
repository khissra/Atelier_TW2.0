<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraints\Date;
use DateTime;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function add(Book $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Book $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

     /**
     * @return Book[]
     */
    public function findById(): array
        {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQueryBuilder('b')
         ->orderBy('b.ref', 'DESC')
         ->setMaxResults(500);

        return $query->getQuery()->getResult();
    }


     /**
     * @return Book[]
     */
    public function findAllBooksByAuthor($username) {
        $qb = $this->createQueryBuilder('b')
            ->join('b.author', 'a') 
            ->where('a.username = :username') 
            ->setParameter('username', $username);
        return $qb->getQuery()->getResult();
    }

    /**
     * @return Book[]
     */
    public function findAllBooksByDate() {
        $date = new DateTime('2023-01-01');
        $qb = $this->createQueryBuilder('b')
        ->where('b.publicationDate >= :date')
        ->setParameter('date', $date);
    return $qb->getQuery()->getResult();
    }


     /**
     * @return Book[]
     */
    public function findAllBooksByAuthor2() {
        $qb = $this->createQueryBuilder('b')
            ->join('b.author', 'a') 
            ->groupBy('a.id');
        return $qb->getQuery()->getResult();
    }
    

//    /**
//     * @return Book[] Returns an array of Book objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Book
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
