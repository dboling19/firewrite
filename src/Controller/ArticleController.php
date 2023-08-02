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
use App\Repository\ArticleRepository;

class ArticleController extends AbstractController
{
  public function __construct(
    private Security $security,
    private EntityManagerInterface $em,
    private ArticleRepository $article_repo,
  ) {}


  /**
   * Create articles
   * 
   * @author Daniel Boling
   */
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
    $article->setSummary($params['summary']);
    $article->setUser($this->security->getUser());
    $article->setDatetime(new Datetime('now'));
    $this->addFlash('success', 'Article Published!');

    $this->em->persist($article);
    $this->em->flush();

    return $this->redirectToRoute('create_article');
  }


  /**
   * Display article.  Accessible from route('home') by clicking a link, searching, or being provided a shared link.
   * Later, for security and user-friendli-ness, article ID's will be hased before being POST.  When coming to this route, the ID will need to be decrypted using an application secret. 
   * For now, simple IDs will be used.
   * 
   * @author Daniel Boling
   */
  #[Route('/article/', name: 'display_article')]
  public function display_article(Request $request): Response
  {
    $id = $request->query->get('article_id');
    $article = $this->article_repo->find($id);
    if (!$article)
    {
      $this->addFlash('error', 'Invalid Article Link');
      return $this->redirectToRoute('home');
    }


    return $this->render('article/display_article.html.twig', [
      'article' => $article,
    ]);
  }
}
