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
//        TODO list all
//        TODO ALSO ONLY ( HIGHEST - 1 ) LEVEL ADMIN CAN SEE THE ROUTES IN THIS CONTROLLER
//        TODO SEE IF YOU CAN AD A PARENT ROUTE CONTROLLER FOR A CLASS AND USE IT AS PARENT: eg instead of writing /agency/edit/{id} you have /edit/{id}
//        TODO FOUND! https://symfony.com/doc/master/bundles/SensioFrameworkExtraBundle/annotations/routing.html#route-prefix

        return $this->render('dashboard/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
}
