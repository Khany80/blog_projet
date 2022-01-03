<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Form\SearchArticleType;
use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class MainController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(ArticlesRepository $repo, Request $request): Response
    {
        $articles = null; 

        $form = $this->createForm(SearchArticleType::class);

        $search = $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $articles = $repo->search($search->get('mots')->getData());
        }

        $showLasted = $repo->lastedArticle();
        $showRandom = $repo->randomArticle([ $showLasted->getId() ]);
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'showLasted' => $showLasted,
            'showRandom' => $showRandom,
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/mentions-legales", name="mentions")
     */
    public function mentions(){
        return $this->render('main/mentions.html.twig');
    }

    /**
     * @Route("/nav", name="nav")
     */
    public function nav(Request $request, ArticlesRepository $repo)
    {
        //$article = null;
        $form = $this->createForm(SearchArticleType::class);

        // $search = $form->handleRequest($request);
        // if ($form->isSubmitted() && $form->isValid()) {
        //     $article = $repo->search($search->get('mots')->getData());
        // }

        $categories = $this->getDoctrine()->getRepository(Categories::class)->findAll();

        return $this->render('main/header.html.twig', [
            'categories'=> $categories,
            'form' => $form->createView(),
            //'article' => $article
        ]);
    }

}
