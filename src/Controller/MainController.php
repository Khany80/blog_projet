<?php

namespace App\Controller;

use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class MainController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(ArticlesRepository $repo): Response
    {
        $showLasted = $repo->lastedArticle();
        $showRandom = $repo->randomArticle([ $showLasted->getId() ]);
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'showLasted' => $showLasted,
            'showRandom' => $showRandom,
        ]);
    }

    /**
     * @Route("/mentions-legales", name="mentions")
     */
    public function mentions(){
        return $this->render('main/mentions.html.twig');
    }
}
