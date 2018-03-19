<?php

namespace App\Controller;

use App\Entity\PasswordResetToken;
use App\Entity\User;
use App\Form\UserDoResetPasswordType;
use App\Form\UserRegistrationType;
use App\Form\UserResetPasswordType;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class UserController.
 */
class UserController extends Controller
{
    /**
     * Registering route.
     *
     * @Route("/register", name="user_registration")
     *
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $passwordEncoder Autowired PasswordEncoder service
     * @param UserService                  $userService     Autowired UserService
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, UserService $userService)
    {
        $user = new User();
        $form = $this->createForm(UserRegistrationType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setActualRole('IS_AWAITING_VALIDATION');

            $confirmationToken = $userService->sendAccountConfirmationEmail($user);

            $user->setConfirmationToken($confirmationToken);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('admin_dashboard');
        }

        return $this->render(
            'user/register.html.twig',
            [
                'registerForm' => $form->createView(),
            ]
        );
    }

    /**
     * Login route.
     *
     * @Route("/login", name="user_login")
     *
     * @param AuthenticationUtils $authUtils Autowired AuthenticationUtils service
     *
     * @return Response
     */
    public function loginAction(AuthenticationUtils $authUtils)
    {
        $error = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();

        return $this->render(
            'user/login.html.twig',
            [
                'last_username' => $lastUsername,
                'error' => $error,
            ]
        );
    }

    /**
     * Account confirmation route.
     *
     * @Route("/user/confirm/{slug}", name="user_confirm_account")
     *
     * @param string      $slug        The confirmation token
     * @param UserService $userService Autowired UserService
     *
     * @throws \Exception            In case token is already used
     * @throws NotFoundHttpException In case token doesn't exists
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirmAccount($slug, UserService $userService)
    {
        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository(User::class);

        $user = $userRepository->findOneBy([
            'confirmationToken' => $slug,
        ]);

        if (!$user) {
            throw $this->createNotFoundException('No user found for token '.$slug);
        }

        $userService->handleAccountConfirmation($user);

        return $this->redirectToRoute('user_login');
    }

    /**
     * Account profile route.
     *
     * @Route("/user/profile", name="user_profile")
     *
     * @return Response
     */
    public function profile()
    {
        $user = $this->getUser();

        return $this->render('user/profile.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * Account password reset route.
     *
     * @Route("/resetpassword", name="user_reset_password")
     *
     * @param Request     $request
     * @param UserService $userService Autowired UserService
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     *
     * @return Response
     */
    public function resetPassword(Request $request, UserService $userService)
    {
        $form = $this->createForm(UserResetPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $userRepository = $em->getRepository(User::class);

            $user = $userRepository->findOneBy(['email' => $form->get('email')->getData()]);

            if (!$user) {
                $this->createNotFoundException('User not found for this email');
            }
            if ($user->getActualRole() === "IS_AWAITING_VALIDATION") {
                $this->createAccessDeniedException('Can\'t reset password on unvalidated account');
            }

            $userService->sendPasswordResetEmail($user);

            return $this->redirectToRoute('user_login');
        }

        return $this->render(
            'user/reset_password.html.twig',
            [
                'resetForm' => $form->createView(),
            ]
        );
    }

    /**
     * Account password do reset route.
     *
     * @Route("/resetpassword/{slug}", name="user_do_reset_password")
     *
     * @param string                       $slug            The confirmation token
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $passwordEncoder Autowired password encoder
     *
     * @return Response
     */
    public function doResetPassword(String $slug, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $em = $this->getDoctrine()->getManager();
        $tokensRepository = $em->getRepository(PasswordResetToken::class);

        $token = $tokensRepository->findOneBy([
            'token' => $slug,
        ]);

        $user = $token->getUser();

        if (!$token) {
            throw $this->createNotFoundException('No password reset token found for token '.$slug);
        }
        if (!$user) {
            throw $this->createNotFoundException('No user found for token '.$slug);
        }
        if ($token->isUsed()) {
            throw new \LogicException('Token already used');
        }

        $form = $this->createForm(UserDoResetPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $form->get('plainPassword')->getData());
            $user->setPassword($password);
            $token->setIsUsed(true);

            $em->flush();

            return $this->redirectToRoute('user_login');
        }

        return $this->render(
            'user/do_reset_password.html.twig',
            [
                'resetForm' => $form->createView(),
            ]
        );
    }
}
