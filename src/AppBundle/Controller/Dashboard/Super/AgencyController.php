<?php

namespace AppBundle\Controller\Dashboard\Super;

use AppBundle\Entity\Agency;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/dashboard/agency")
 */
class AgencyController extends Controller
{
    /**
     * @Route("/", name="agency_list")
     */
    public function indexAction(Request $request)
    {
//        TODO list all ->DONE
//        TODO ALSO ONLY HIGHEST LEVEL ADMIN CAN SEE THE ROUTES IN THIS CONTROLLER ^> DONE
//        TODO split views into a separate entity, register IP (or device id) -> DONE
//        TODO add agency
//        TODO

        $qb = $this->getDoctrine()->getRepository(Agency::class)->createQueryBuilder('u')
            ->orderBy('u.createdAt', 'DESC')
        ;
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            20
        );
        return $this->render('dashboard/agency/index.html.twig', [
            'pagination' => $pagination,
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
    /**
     * @Route("/admin/agency/edit/{id}", name="edit_agency")
     */
    public function editAction(Request $request)
    {
//        TODO edit single
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
    /**
     * @Route("/admin/agency/disable/{id}", name="disable_agency")
     */
    public function disableAction(Request $request)
    {
//        TODO set status to 0 and also pass a message to the agency (setStatus(0), setCallingMessage($somevar) eg Your agency has been disabled. $message
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
    /**
     * @Route("/admin/agency/add", name="add_agency")
     */
    public function addAction(Request $request)
    {
//        TODO add single
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
    /**
     * @Route("/admin/agency/remove", name="remove_agencies")
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
