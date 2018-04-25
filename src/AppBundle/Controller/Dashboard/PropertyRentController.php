<?php

namespace AppBundle\Controller\Dashboard;

use AppBundle\Entity\Area;
use AppBundle\Entity\House;
use AppBundle\Entity\State;
use AppBundle\Repository\StateRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


/**
 * @Route("/dashboard/proprent")
 */
class PropertyRentController extends Controller
{
    /**
     * @Route("/", name="proprent_list")
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
            ->andWhere('u.forSale = :forSale')
            ->setParameter('forSale', 0)
            ->orderBy('u.createdAt', 'DESC')
        ;
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            20
        );
        $areas = $this->getDoctrine()->getRepository(Area::class)->findAll();
        $states = $this->getDoctrine()->getRepository(State::class)->findAll();


        return $this->render('dashboard/proprent/index.html.twig', [
            'pagination' => $pagination,
            'states' => $states,
            'areas' => $areas,
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
    /**
     * @Route("/edit/{id}", name="proprent_edit")
     */
    public function editAction(House $house, Request $request)
    {
        $form = $this->createForm("AppBundle\Form\AdminHouseFormType", $house);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'House updated');
            return $this->redirectToRoute('proprent_edit', array('id' => $house->getId()));
        }
        return $this->render('dashboard/proprent/edit.html.twig', [
            'house' => $house,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/add", name="proprent")
     */
    public function addAction(House $house, Request $request)
    {
//        TODO add single

        $form = $this->createForm("AppBundle\Form\AdminHouseFormType", $house);
        $form->handleRequest($request);
        return $this->render('default/index.html.twig', [
            'proprent' => $house,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/admin/proprent/remove", name="remove_houses")
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
