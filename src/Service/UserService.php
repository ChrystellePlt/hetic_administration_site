<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class UserService.
 */
class UserService
{
    /**
     * @var EntityManagerInterface Autowired Doctrine Service
     */
    private $em;
    /**
     * @var \Swift_Mailer Autowired SwitMailer Service
     */
    private $mailer;
    /**
     * @var \Twig_Environment Autowired Twig Service
     */
    private $twig;

    /**
     * UserService constructor.
     *
     * @param EntityManagerInterface $em     Autowired Doctrine Service
     * @param \Swift_Mailer          $mailer Autowired SwitMailer Service
     * @param \Twig_Environment      $twig   Autowired Twig Service
     */
    public function __construct(EntityManagerInterface $em, \Swift_Mailer $mailer, \Twig_Environment $twig)
    {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * Generates and sets confirmation token for user then sends mail with confirmation link.
     *
     * @param User $user The user for who we generate the token
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     *
     * @return string The confirmation token
     */
    public function sendAccountConfirmationEmail(User $user): string
    {
        $hashBase = $salt = uniqid(mt_rand(), true).$user->getUsername().$user->getEmail();
        $confirmationToken = hash('sha256', $hashBase);

        $user->setConfirmationToken($confirmationToken);

        $message = (new \Swift_Message('HETIC - Confirmation de votre compte'))
            ->setFrom('test@test.com', 'HETIC')
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

    /**
     * Confirms account when visiting the validation link.
     *
     * @param User $user User to validate
     *
     * @throws \Exception
     */
    public function handleAccountConfirmation(User $user): void
    {
        if (!in_array('IS_AWAITING_VALIDATION', $user->getRoles(), true)) {
            throw new \Exception('Token already used');
        }
        $user->setRoles(['ROLE_STUDENT']);

        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * Set role to ROLE_ADMIN for specified user.
     *
     * @param User $user User to set as administrator
     */
    public function setUserAsAdministrator(User $user): void
    {
        $user->setRoles(['ROLE_ADMIN']);

        $this->em->persist($user);
        $this->em->flush();
    }
}
