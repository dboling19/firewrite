<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;


class RegistrationController extends AbstractController
{
  public function __construct(
    private EntityManagerInterface $em, 
    private EmailVerifier $email_verifier,
    private UserPasswordHasherInterface $user_pass_hasher,
    private UserRepository $user_repo,
  ) { }

  
  /**
   * Controls user registration
   * 
   * @author Daniel Boling
   */
  #[Route('/auth/register/', name: 'app_register')]
  public function register(Request $request, Security $security): Response
  {
    if ($this->getUser())
    // if user is already logged in, give access to re-verification.
    {
      return $this->render('auth/verify_email_error.html.twig');
    }

    if (!$request->request->all())
    // form has't been submitted yet
    {
      return $this->render('auth/register.html.twig');
    }

    $params = $request->request->all();
    $user = new User;
    $user->setEmail($params['email']);
    $user->setUsername($params['username']);
    // encode the plain password
    $user->setPassword(
      $this->user_pass_hasher->hashPassword(
        $user,
        $params['password'],
      )
    );

    $this->em->persist($user);
    $this->em->flush();

    $user = $this->user_repo->findOneBy(['username' => $user->getUsername()]);


    $this->email_verifier->sendEmailConfirmation('app_verify_email', $user,
      (new TemplatedEmail())
        ->from(new Address('bot@firewrite.com', 'Firewrite Bot'))
        ->to($user->getEmail())
        ->subject('Firewrite: Email Verification')
        ->htmlTemplate('auth/confirmation_email.html.twig')
        ->context([
          'user' => $user,
        ])
    );
    // send a registration email
    
    $security->login($user, 'form_login');
    return $this->redirectToRoute('home');
  }

  
  /**
   * Allows users to request a new verification email
   * 
   * @author Daniel Boling
   */
  #[Route('/request-verify-email/', name: 'app_request_verify_email')]
  public function request_verification_email(): Response
  {   
    // generate a signed url and email it to the user
    $user =  $this->getUser();
    $this->email_verifier->sendEmailConfirmation(
      'app_verify_email',
      $user,
      (new TemplatedEmail())
        ->from(new Address('bot@firewrite.com', 'Firewrite'))
        ->to($user->getEmail())
        ->subject('Firewrite: Email Verification')
        ->htmlTemplate('auth/confirmation_email.html.twig')
    );
    return new Response('success');
  }


  /**
   * Controls account verification
   * 
   * @author Daniel Boling
   */
  #[Route('/auth/verify_email/', name: 'app_verify_email')]
  public function verifyUserEmail(Request $request): Response
  {
    $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

    // validate email confirmation link, sets User::isVerified=true and persists
    try {
      $this->email_verifier->handleEmailConfirmation($request, $this->getUser());
    } catch (VerifyEmailExceptionInterface $exception) {
      $this->addFlash('verify_email_error', $exception->getReason());

      return $this->redirectToRoute('app_register');
    }

    // @TODO Change the redirect on success and handle or remove the flash message in your templates
    $this->addFlash('success', 'Your email address has been verified.');

    return $this->redirectToRoute('app_login');
  }
}


// EOF
