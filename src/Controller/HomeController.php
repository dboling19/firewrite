<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticleRepository;

class HomeController extends AbstractController
{
  public function __construct (private ArticleRepository $article_repo) {}

  #[Route('/', name: 'home')]
  public function home(): Response
  {
    $articles = $this->article_repo->findBy([], ['id' => 'desc']);
    return $this->render('home/home.html.twig', [
      'articles' => $articles,
    ]);
  }
}
