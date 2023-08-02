<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
  public function __construct(
    private UrlGeneratorInterface $urlGenerator,
  ) { }

  public function handle(Request $request, AccessDeniedException $access_denied_exception): ?Response
  {
    $request->getSession()->getFlashBag()->add('note', 'Your email needs to be verified in order to access this page.');

    return new RedirectResponse($this->urlGenerator->generate('app_register', [], UrlGeneratorInterface::ABSOLUTE_URL));
  }
}