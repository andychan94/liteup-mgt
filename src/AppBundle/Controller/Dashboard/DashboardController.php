<?php

namespace AppBundle\Controller\Dashboard;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


/**
 * @Route("/dashboard")
 */
class DashboardController extends Controller
{
    /**
     * @Route("/", name="dashboard_home")
     */
    public function indexAction(Request $request)
    {
        $blogs = $this->getDoctrine()->getRepository('AppBundle:Blog')->findBy(['isAdminDashboard' => true], ['blogCreatedAt' => 'DESC']);

        $notifications = $this->getDoctrine()->getRepository('AppBundle:Notification')->findBy(['isCheck' => false], ['createdAt' => 'DESC']);

        return $this->render('dashboard/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
            'blogs' => $blogs,
            'notifications' => $notifications,
        ]);
    }

    /**
     * @Route("/support-message", name="support_message")
     */
    public function supportMessageAction(Request $request, \Swift_Mailer $mailer)
    {
        $support_message = $request->get('support_message');
        $mail_user = $this->getParameter('mailer_user');
        $message = (new \Swift_Message('Support Message'))
            ->setFrom($mail_user)
            ->setTo($mail_user)
            ->setBody(
                $this->renderView(
                // app/Resources/views/Emails/registration.html.twig
                    'Emails/support-email.html.twig',
                    array(
                        'name' => $this->getUser()->getName(),
                        'email' => $this->getUser()->getEmail(),
                        'text' => $support_message,
                    )
                ),
                'text/html'
            );

        $mailer->send($message);

        $this->addFlash('success', 'Your message has been successfully sent ');
        return $this->redirectToRoute('dashboard_home');
    }
}
