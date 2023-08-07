<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class UserController extends AbstractController
{
  public function __construct(
    private UserRepository $user_repo,
  ) { }


  /**
   * Display use profile
   * 
   * @author Daniel Boling
   */
  #[Route('/users/profile', name: 'display_user')]
  public function view_profile(Request $request): Response
  {
    $id = $request->query->get('user_id');
    $user = $this->user_repo->find($id);
    if (!$user)
    {
      $this->addFlash('error', 'Invalid user link');
      return $this->redirectToRoute('home');
    }
        
    return $this->render('user/profile_layout.html.twig', [
      'user' => $user,
    ]);
  }

  /**
   * Gets the total number of articles written by the user
   * 
   * @author Daniel Boling
   */
  public function articles_total(Request $request, ?string $user_id): Response
  {
    $articles_total = count($this->user_repo->find($user_id)->getArticles());

    return $this->render('stats/_articles_total.html.twig', [
      'articles_total' => $articles_total,
    ]);
  }
}
