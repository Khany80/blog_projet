<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Commentaires;
use App\Form\CommentaireFormType;
use App\Repository\ArticlesRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticlesController extends AbstractController
{
    /**
     * @Route("/articles", name="articles")
     */
    public function index(Request $request, PaginatorInterface $paginator)
    {
        $donnees = $this->getDoctrine()->getRepository(Articles::class)->findBy([],
    ['created_at' => 'desc']);
        $articles = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            4
        );
        return $this->render('articles/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/article-{id}", name="article")
     */
    public function article($id, Request $request)
    {
       $article = $this->getDoctrine()->getRepository(Articles::class)->findOneBy(['id' => $id]);

       if (!$article) {
           throw $this->createNotFoundException("L'article n'existe pas");
       }
        
       $comment = new Commentaires();

       $form = $this->createForm(CommentaireFormType::class, $comment);

       $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {
           $comment->setArticles($article);
           $comment->setCreatedAt(new \DateTime('now'));

           $doctrine = $this->getDoctrine()->getManager();
           $doctrine->persist($comment);
           $doctrine->flush();

           return $this->redirectToRoute('article', ['id' => $article->getID()]);
       }

       return $this->render('articles/article.html.twig', [
           'article' => $article,
           'commentForm' =>$form->createView(),
           
           ]);
    }

    /**
     * @Route("article-by-category/{id}", name="article-by-category")
     */
    public function getIdCategory(PaginatorInterface $paginator, ArticlesRepository $repo, Request $request, $id)
    {
        $donnees = $repo->findByCategory($id);
        $articles = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            4
        );
        return $this->render('articles/articleCategory.html.twig', [
        'articles' => $articles
        ]);
    }

}
