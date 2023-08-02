<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\ArticleRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
  public function __construct(
    private ArticleRepository $article_repo,
    private EntityManagerInterface $em,
  ) { }
  /**
   * Creates comments under articles and assigns them accordingly.
   * Tracks date, user, and rating.
   * 
   * @author Daniel Boling
   */
  #[Route('/create_comment/', name: 'create_comment')]
  public function create_comment(Request $request): Response
  {
    if (!defined($request->query->get('article_id')));
    // if no id is provided.
    {
      $this->addFlash('error', 'An unexpected error occured.');
      $this->redirectToRoute('home');
    }
    $article_id = $request->query->get('article_id');

    if (!$request->request->all())
    {
      $this->redirectToRoute('display_article', [
        'article_id' => $article_id,
      ]);
    }

    $params = $request->request->all();
    $article_id = $request->query->get('article_id');
    $comment = new Comment;
    $comment->setBody($params['body']);
    $comment->setDatetime(new DateTime('now'));
    $comment->setUser($this->getUser());
    $comment->setArticle($this->article_repo->find($article_id));
    $this->em->persist($comment);
    $this->em->flush();

    return $this->redirectToRoute('display_article', [
      'article_id' => $article_id,
    ]);
  }
}
