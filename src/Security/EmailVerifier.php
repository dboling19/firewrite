<?php

namespace App\Security;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class EmailVerifier
{
    public function __construct(
        private VerifyEmailHelperInterface $email_verifier_helper,
        private MailerInterface $mailer,
        private EntityManagerInterface $em,
    ) {
    }

    public function sendEmailConfirmation(string $verifyEmailRouteName, UserInterface $user, TemplatedEmail $email): void
    {
        $signatureComponents = $this->email_verifier_helper->generateSignature(
            $verifyEmailRouteName,
            $user->getId(),
            $user->getEmail(),
        );

        $context = $email->getContext();
        $context['signedUrl'] = $signatureComponents->getSignedUrl();
        $context['expiresAtMessageKey'] = $signatureComponents->getExpirationMessageKey();
        $context['expiresAtMessageData'] = $signatureComponents->getExpirationMessageData();

        $email->context($context);

        $this->mailer->send($email);
    }

    /**
     * @throws VerifyEmailExceptionInterface
     */
    public function handleEmailConfirmation(Request $request, UserInterface $user): void
    {
        $this->email_verifier_helper->validateEmailConfirmation($request->getUri(), $user->getId(), $user->getEmail());

        $current_roles = $user->getRoles();
        $roles[] = 'ROLE_VERIFIED';
        $new_roles = array_merge($current_roles, $roles); 
        $user->setRoles(array_unique($new_roles));

        $this->em->persist($user);
        $this->em->flush();
    }
}
