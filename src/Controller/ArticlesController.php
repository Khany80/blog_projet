<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Categories;
use App\Entity\Commentaires;
use App\Entity\Tags;
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
        $donnees = $this->getDoctrine()->getRepository(Articles::class)->findBy(['active' => true], ['created_at' => 'desc']);
        $articles = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            4
        );
        $categories = $this->getDoctrine()->getRepository(Categories::class)->findAll();
        $tags = $this->getDoctrine()->getRepository(Tags::class)->findAll();
        return $this->render('articles/index.html.twig', [
            'articles' => $articles,
            'categories'=> $categories,
            'tags' => $tags,
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

       $categories = $this->getDoctrine()->getRepository(Categories::class)->findAll();
       $tags = $this->getDoctrine()->getRepository(Tags::class)->findAll();

       return $this->render('articles/article.html.twig', [
           'article' => $article,
           'commentForm' =>$form->createView(),
           'categories'=> $categories,
           'tags' => $tags,
           
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

        /**
     * @Route("article-by-tag/{id}", name="article-by-tag")
     */
    public function getIdTag(PaginatorInterface $paginator, ArticlesRepository $repo, Request $request, $id)
    {
        $donnees = $repo->findByTag($id);
        $articlesTag = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            4
        );
        return $this->render('articles/articleTag.html.twig', [
        'articlesTag' => $articlesTag,
        ]);
    }

}
