<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
      $this->denyAccessUnlessGranted(attribute:'IS_AUTHENTICATED_FULLY');
      return $this->render('admin/dashboard.html.twig');
    }
}
