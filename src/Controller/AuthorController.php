<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Author;
use App\Repository\AuthorRepository;

class AuthorController extends AbstractController
{
  /**
   * Route('/author', name: 'app_author')
    */
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    /**
   *Route('/list/{var}', name: 'list_author')
   */
    public function listAuthor($var)
    {
        $authors = array(
            array('id' => 1, 'username' => ' Victor Hugo','email'=> 'victor.hugo@gmail.com', 'nb_books'=> 100),
            array ('id' => 2, 'username' => 'William Shakespeare','email'=>
                'william.shakespeare@gmail.com','nb_books' => 200),
            array('id' => 3, 'username' => ' Taha Hussein','email'=> 'taha.hussein@gmail.com','nb_books' => 300),
        );

        return $this->render("author/list.html.twig",
            array('variable'=>$var,
                'tabAuthors'=>$authors
                ));
    }

    /**
   *Route('/author/{id}', name: 'author_details')
   */
public function authorDetails($id)
{
    $authors = array(
        array('id' => 1, 'username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com', 'nb_books' => 100,'image'=>'imgs/Victor-Hugo.jpg'),
        array('id' => 2, 'username' => 'William Shakespeare', 'email' => 'william.shakespeare@gmail.com', 'nb_books' => 200,'image'=>'imgs/william-shakespeare.jpg'),
        array('id' => 3, 'username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300,'image'=>'imgs/TAHA_HUSSEIN.jpg'),
    );

    
    $author = null;
    foreach ($authors as $a) {
        if ($a['id'] == $id) {
            $author = $a;
            break;
        }
    }

    return $this->render('author/showAuthor.html.twig', [
        'author' => $author,
    ]);
}

/**
   *Route('/listAuthor', name: 'authors')
   */
public function list(AuthorRepository $repository)
{
    $authors = $repository->findAll();
    return $this->render("author/listAuthors.html.twig",
        array(
            'tabAuthors'=>$authors
        ));
}

/**
   *Route('/add', name: 'add_authors')
   */
public function addAuthor(ManagerRegistry $managerRegistry)
{
    $author= new Author();
    $author->setEmail("author6@gmail.com");
    $author->setUsername("author6");
   // $em= $this->getDoctrine()->getManager();
    $em= $managerRegistry->getManager();
    $em->persist($author);
    $em->flush();
    return $this->redirectToRoute("authors");

}


/**
   *Route('/update/{id}', name: 'update_authors')
   */
public function updateAuthor($id,AuthorRepository $repository,ManagerRegistry $managerRegistry)
{
    $author= $repository->find($id);
    $author->setEmail("author7@gmail.com");
    $author->setUsername("author7");
    // $em= $this->getDoctrine()->getManager();
    $em= $managerRegistry->getManager();
    $em->flush();
    return $this->redirectToRoute("authors");
}

/**
   *Route('/remove/{id}', name: 'remove_authors')
   */
public function deleteAuthor(AuthorRepository $repository,$id,
                             ManagerRegistry $managerRegistry)
{
    $author= $repository->find($id);
    $em = $managerRegistry->getManager();
    $em->remove($author);
    $em->flush();
    return $this->redirectToRoute("authors");
}


}
