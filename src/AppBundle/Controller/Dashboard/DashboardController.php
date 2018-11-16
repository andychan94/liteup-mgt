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
        $blogs = $this->getDoctrine()->getRepository('AppBundle:Blog')->findBy(['isAdminDashboard' => true],['blogCreatedAt' => 'DESC']);

        $notifications = $this->getDoctrine()->getRepository('AppBundle:Notification')->findBy(['isCheck' => false],['createdAt' => 'DESC']);

        return $this->render('dashboard/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'blogs' => $blogs,
            'notifications' => $notifications,
        ]);
    }
}
