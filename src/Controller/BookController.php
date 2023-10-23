<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Book;
use App\Form\BookType;
use Doctrine\ORM\EntityManagerInterface;

class BookController extends AbstractController
{
    /**
     * @Route("/book", name="app_book")
     */
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

   /**
 * @Route("/listBooks", name="list_books")
 */
public function list(BookRepository $repository)
{
   //$books = $repository->findAll();
    //$books = $repository->findAllBooksByAuthor(1);
    //$books2 = $repository->findAllBooksByAuthor2();
     $books = $repository->findAllBooksByDate();
    return $this->render("book/listBooks.html.twig", [
        'books' => $books
    ]);

    /*  return $this->render("book/list.html.twig",
        array('books'=>$repository->findBy(['published'=>false])));*/
}


  



/**
 * @Route("/addBook", name="add_book")
 */
public function addBook(Request $request,ManagerRegistry $managerRegistry){
    $book= new Book();
    $form= $this->createForm(BookType::class, $book);
    $form->handleRequest($request);
     if($form->isSubmitted()){
         $nbBooks= $book->getAuthor()->getNbBooks();
        // var_dump($nbBooks).die();
         $book->getAuthor()->setNbBooks($nbBooks+1);
        $book->setPublished(true);
        $em = $managerRegistry->getManager();
        $em->persist($book);
        $em->flush();
        return  new Response("Done!");

    }
     //1ere methode
      /*return $this->render('book/add.html.twig',
      array('formBook'=>$form->createView()));*/
        //2eme methode
        return $this->renderForm('book/add.html.twig',
        array('formBook'=>$form));


    //1ere methode
     //return $this->render('book/add.html.twig',array("formulaireBook"=>$form->createView()));
    //2eme methode
   // return $this->renderForm('book/add.html.twig',array("formulaireBook"=>$form));
}

/**
 * @Route("/updateBook/{ref}", name="update_book")
 */
public function updateBook($ref,BookRepository $repository,Request  $request, ManagerRegistry $managerRegistry)
{   $book= $repository->find($ref) ;
    $form= $this->createForm(BookType::class, $book);
    $form->handleRequest($request);
    if($form->isSubmitted()){
        $nbBooks= $book->getAuthor()->getNbBooks();
        $book->getAuthor()->setNbBooks($nbBooks+1);
        $book->setPublished(true);
        $em = $managerRegistry->getManager();
        $em->flush();
        return  $this->redirectToRoute("list_books");
    }
    return $this->renderForm('book/update.html.twig',
        array('formBook'=>$form));
}
/**
 * @Route("/deleteBook/{ref}", name="delete_book")
 */
public function deleteBook($ref,BookRepository $repository,Request  $request, EntityManagerInterface $entityManager)
{   
    $book = $repository->find($ref);
    $entityManager->remove($book);
    $entityManager->flush();
    return $this->redirectToRoute("list_books");
}
/**
 * @Route("/showBook/{ref}", name="show_book")
 */
public function showBook($ref,BookRepository $repository,Request  $request, ManagerRegistry $managerRegistry)
{   
    $book = $repository->find($ref);
    if (!$book) {
        throw $this->createNotFoundException('Le livre n\'existe pas.');
    }
    
    return $this->render('book/showBook.html.twig', [
        'book' => $book,
    ]);
}
}