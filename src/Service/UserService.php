<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    private $em;
    private $mailer;
    private $twig;

    public function __construct(EntityManagerInterface $em, \Swift_Mailer $mailer, \Twig_Environment $twig)
    {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function sendAccountConfirmationEmail(User $user): string
    {
        $hashBase = $salt = uniqid(mt_rand(), true).$user->getUsername().$user->getEmail();
        $confirmationToken = hash('sha256', $hashBase);

        $user->setConfirmationToken($confirmationToken);

        $message = (new \Swift_Message('HETIC - Confirmation de votre compte'))
            ->setFrom('test@test.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->twig->render(
                    'email/account_confirmation.html.twig',
                    [
                        'name' => $user->getFirstName(),
                        'token' => $confirmationToken,
                    ]
                ),
                'text/html'
            );

        $this->mailer->send($message);

        return $confirmationToken;
    }

    public function handleAccountConfirmation(User $user)
    {
        if (!in_array('IS_AWAITING_VALIDATION', $user->getRoles(), true)) {
            throw new \Exception('Token already used');
        }
        $user->setRoles(['ROLE_STUDENT']);

        $this->em->persist($user);
        $this->em->flush();
    }
}
