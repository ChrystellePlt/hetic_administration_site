<?php

namespace App\Controller;

use App\Entity\AccompanyingRequest;
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
     * @Route("/dashboard", name="admin_dashboard")
     */
    public function index()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin/dashboard.html.twig');
    }

    /**
     * @Route("/requests/add", name="admin_add_request")
     */
    public function addRequest(Request $request)
    {
        $accompanyingRequest = new AccompanyingRequest();
        $form = $this->createForm(AccompanyingRequestType::class, $accompanyingRequest);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($accompanyingRequest);
            $em->flush();

            return $this->redirectToRoute('admin_dashboard');
        }

        return $this->render(
            'admin/create_request.html.twig',
            [
                'requestForm' => $form->createView(),
            ]
        );
    }
}
