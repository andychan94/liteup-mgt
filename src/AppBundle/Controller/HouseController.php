<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HouseController extends Controller
{
    /**
     * @Route("/admin/house", name="list_agencies")
     */
    public function indexAction(Request $request)
    {
//        TODO list all
//        TODO ALSO ONLY ( HIGHEST - 1 ) LEVEL ADMIN CAN SEE THE ROUTES IN THIS CONTROLLER
//        TODO SEE IF YOU CAN AD A PARENT ROUTE CONTROLLER FOR A CLASS AND USE IT AS PARENT: eg instead of writing /agency/edit/{id} you have /edit/{id}
//        TODO FOUND! https://symfony.com/doc/master/bundles/SensioFrameworkExtraBundle/annotations/routing.html#route-prefix
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
    /**
     * @Route("/admin/house/edit/{id}", name="edit_house")
     */
    public function editAction(Request $request)
    {
//        TODO edit single
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
    /**
     * @Route("/admin/house/add", name="add_house")
     */
    public function addAction(Request $request)
    {
//        TODO add single
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
    /**
     * @Route("/admin/house/remove", name="remove_houses")
     */
    public function removeAction(Request $request)
    {
//        TODO remove many
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

//    TODO SPECIAL: Add Multiple from a csv file : instead of adding agencies manually, multiple agencies can be added at the same time using a csv file
    /**
     * @Route("/admin/agency/csv", name="csv_add_agencies")
     */
    public function csvAction(Request $request)
    {
//        TODO csv file upload; handler (insert into DB)
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
}
