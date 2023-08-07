<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\SecurityBundle\Security;


class LoginController extends AbstractController
{
  #[Route('/auth/login/', name: 'app_login')]
  public function login(AuthenticationUtils $auth_utils): Response
  {
    // get login error if exists
    $error = $auth_utils->getLastAuthenticationError();

    // last username entered by user
    $last_username = $auth_utils->getLastUsername();

    return $this->render('auth/login.html.twig', [
      'last_username' => $last_username,
      'error' => $error,
    ]);
  }

  /**
   * Logs out currently logged in user
   * Method required for functionality.  Any logout logic goes in /src/EventListener/LogoutSubscriber.php
   */
  #[Route('/auth/logout/', name: 'app_logout')]
  public function logout()
  {
    // these are not the methods you are looking for
  } 
}
