<?php

namespace App\Controller;

use App\Entity\AccompanyingRequest;
use App\Entity\User;
use App\Form\AccompanyingRequestType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminController.
 *
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     * Admin dashboard route.
     *
     * @Route("/dashboard", name="admin_dashboard")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin/dashboard.html.twig');
    }

    /**
     * Admin accompanying request listing route.
     *
     * @Route("/requests", name="admin_list_requests")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listRequests(Request $request)
    {
        $accompanyingRequestsRepository = $this->getDoctrine()->getManager()->getRepository(AccompanyingRequest::class);
        $requestsList = $accompanyingRequestsRepository->findAll();

        return $this->render(
            'admin/list_requests.html.twig',
            [
                'requestsList' => $requestsList,
            ]
        );
    }

    /**
     * Admin accompanying request creation route.
     *
     * @Route("/requests/add", name="admin_add_request")
     *
     * @param Request       $request
     * @param \Swift_Mailer $mailer  Autowired SwiftMailer Service
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addRequest(Request $request, \Swift_Mailer $mailer)
    {
        $accompanyingRequest = new AccompanyingRequest();
        $form = $this->createForm(AccompanyingRequestType::class, $accompanyingRequest);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($accompanyingRequest);
            $em->flush();

            $message = (new \Swift_Message('HETIC - Votre journée chez nous'))
                ->setFrom('test@test.com')
                ->setTo($form->get('email')->getData())
                ->setBody(
                    $this->renderView(
                        'email/new_request.html.twig',
                        [
                            'name' => $form->get('firstName')->getData(),
                            'requestDate' => $form->get('date')->getData(),
                        ]
                    ),
                    'text/html'
                );

            $mailer->send($message);

            return $this->redirectToRoute('admin_dashboard');
        }

        return $this->render(
            'admin/create_request.html.twig',
            [
                'requestForm' => $form->createView(),
            ]
        );
    }

    /**
     * Admin accompanying request modification route.
     *
     * @Route("/requests/modify/{slug}", name="admin_modify_request")
     *
     * @param string  $slug    The ID of the request to modify
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function modifyRequest(string $slug, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $accompanyingRequestsRepository = $em->getRepository(AccompanyingRequest::class);
        $accompanyingRequest = $accompanyingRequestsRepository->find($slug);

        if (!$accompanyingRequest) {
            throw $this->createNotFoundException('No request found for id '.$slug);
        }

        $form = $this->createForm(AccompanyingRequestType::class, $accompanyingRequest);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($accompanyingRequest);
            $em->flush();

            return $this->redirectToRoute('admin_list_requests');
        }

        return $this->render(
            'admin/modify_request.html.twig',
            [
                'requestForm' => $form->createView(),
                'request' => $accompanyingRequest,
            ]
        );
    }

    /**
     * Admin accompanying request modification route.
     *
     * @Route("/requests/delete/{slug}", name="admin_delete_request")
     *
     * @param string  $slug    The ID of the request to delete
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteRequest(string $slug, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $accompanyingRequestsRepository = $em->getRepository(AccompanyingRequest::class);
        $accompanyingRequest = $accompanyingRequestsRepository->find($slug);

        if (!$accompanyingRequest) {
            throw $this->createNotFoundException('No request found for id '.$slug);
        }

        $em->remove($accompanyingRequest);
        $em->flush();

        return $this->redirectToRoute('admin_list_requests');
    }

    /**
     * Admin students listing route.
     *
     * @Route("/students", name="admin_list_students")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listStudents(Request $request)
    {
        $usersRepository = $this->getDoctrine()->getManager()->getRepository(User::class);
        $studentsList = $usersRepository->findAllStudentUsers();

        return $this->render(
            'admin/list_students.html.twig',
            [
                'studentsList' => $studentsList,
            ]
        );
    }
}
