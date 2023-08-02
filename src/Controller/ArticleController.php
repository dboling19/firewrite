<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use \Datetime;
use App\Entity\Article;

class ArticleController extends AbstractController
{
  public function __construct(
    private Security $security,
    private EntityManagerInterface $em
  ) {}

  #[Route('/articles/new', name: 'create_article')]
  public function create_article(Request $request): Response
  {
    
    if (!$request->request->all())
    {
      return $this->render('article/create_article.html.twig');
    }

    $params = $request->request->all();
    $errors = [];
    if ($params['title'] == '') { $errors[] = 'Title is required.'; }

    $article = new Article;
    $article->setTitle($params['title']);
    $article->setBody($params['body']);
    $article->setUser($this->security->getUser());
    $article->setDate(new Datetime('now'));

    $this->em->persist($article);
    $this->em->flush();

    return $this->redirectToRoute('create_article');
  }
}
