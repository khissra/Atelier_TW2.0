<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraints\Date;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Request;
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

    public function searchBook($value)
    {
        return $this->createQueryBuilder('b')
            ->where('b.ref LIKE :ref')
            ->setParameter('ref', '%'.$value.'%')
            ->getQuery()
            ->getResult();
    }
    public function booksListByAuthors()
{
    $qb = $this->createQueryBuilder('b')
        ->leftJoin('b.author', 'a')
        ->orderBy('a.username', 'ASC') 
        ->addOrderBy('b.title', 'ASC'); 

    return $qb->getQuery()->getResult();
}
public function listBooksPublishedBefore2023WithAuthors()
{
    $qb = $this->createQueryBuilder('b')
        ->leftJoin('b.author', 'a')
        ->where('b.publicationDate < :year')
        ->andWhere('a.nbBooks > 10')
        ->setParameter('year', new \DateTime('2023-01-01')) 
        ->orderBy('a.username', 'ASC') 
        ->addOrderBy('b.title', 'ASC');

    return $qb->getQuery()->getResult();
}
public function updateCategoryFromScienceFictionToRomance(EntityManagerInterface $entityManager)
{
    $qb = $entityManager->createQueryBuilder();
    
    $qb->update('App\Entity\Book', 'b')
       ->set('b.category', ':newCategory')
       ->where('b.category = :oldCategory')
       ->setParameter('newCategory', 'Romance')
       ->setParameter('oldCategory', 'Science-Fiction');
    
    $query = $qb->getQuery();
    $query->execute();
}
public function countRomanceBooks()
{
    $qb = $this->createQueryBuilder('b')
        ->select('COUNT(b.id)')
        ->where('b.category = :category')
        ->setParameter('category', 'Romance');

    return $qb->getQuery()->getSingleScalarResult();
}
public function findBooksPublishedBetweenDates($startDate, $endDate)
{
    $qb = $this->createQueryBuilder('b')
        ->where('b.publicationDate >= :startDate')
        ->andWhere('b.publicationDate <= :endDate')
        ->setParameter('startDate', $startDate)
        ->setParameter('endDate', $endDate);

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
