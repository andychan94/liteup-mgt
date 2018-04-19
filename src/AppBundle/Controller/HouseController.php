<?php

namespace AppBundle\Controller;

use AppBundle\Entity\House;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


/**
 * @Route("/dashboard/house")
 */
class HouseController extends Controller
{
    /**
     * @Route("/", name="house_list")
     */
    public function indexAction(Request $request)
    {
////        dTODO list all -> DONE
////        dTODO ALSO ONLY (  HIGHEST - 1 ) LEVEL ADMIN CAN SEE THE ROUTES IN THIS CONTROLLER -> DONE
////        dTODO SEE IF YOU CAN AD A PARENT ROUTE CONTROLLER FOR A CLASS AND USE IT AS PARENT: eg instead of writing /agency/edit/{id} you have /edit/{id} -> DONE
        $agencyKey = 'u.agency';
        $agencyValue = $this->getUser();
        // if getUser() is super admin, show all houses from all agencies
        if (in_array('ROLE_SUPER_ADMIN',$this->getUser()->getRoles())) {
            $agencyKey = '1';
            $agencyValue = 1;
        }
        $qb = $this->getDoctrine()->getRepository(House::class)->createQueryBuilder('u')
            ->where($agencyKey.' = :agency')
            ->setParameter('agency', $agencyValue)
            ->orderBy('u.createdAt', 'DESC')
        ;
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            20
        );
        return $this->render('dashboard/house/index.html.twig', [
            'pagination' => $pagination,
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
    /**
     * @Route("/edit/{id}", name="house_edit")
     */
    public function editAction(House $house, Request $request)
    {
//        TODO edit single

        $form = $this->createForm("AppBundle\Form\AdminHouseFormType", $house);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'House updated');
            return $this->redirectToRoute('house_edit', array('id' => $house->getId()));
        }
        return $this->render('dashboard/house/edit.html.twig', [
            'house' => $house,
            'form' => $form->createView(),
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
