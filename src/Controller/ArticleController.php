<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
  #[Route('/new/article', name: 'create_article')]
  public function create_article(): Response
  {
    return $this->render('article/create_article.html.twig');
  }
}
