<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRegistrationType;
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
            $user->setRoles(['IS_AWAITING_VALIDATION']);

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
     * @param UserService $userService The autowired UserService
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

        return $this->render('user/profile.html.twig',[
            'user' => $user,
        ]);
    }
}
