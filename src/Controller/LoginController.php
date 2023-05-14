<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class LoginController extends AbstractController
{
  #[Route('/login', name: 'app_login')]
  public function index(AuthenticationUtils $auth_utils): Response
  {
    // get login error if exists
    $error = $auth_utils->getLastAuthenticationError();

    // last username entered by user
    $last_username = $auth_utils->getLastUsername();

    return $this->render('login/login_form.html.twig', [
      'last_username' => $last_username,
      'error' => $error,
    ]);
  }
}
