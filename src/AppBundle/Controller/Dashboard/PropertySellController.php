<?php

namespace AppBundle\Controller\Dashboard;

use AppBundle\Entity\Area;
use AppBundle\Entity\House;
use AppBundle\Entity\State;
use AppBundle\Repository\StateRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


/**
 * @Route("/dashboard/propsell")
 */
class PropertySellController extends Controller
{
    /**
     * @Route("/", name="propsell_index")
     */
    public function indexAction(Request $request)
    {
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
            ->andWhere('u.selling = :selling')
            ->setParameter('selling', true)
            ->andWhere('u.deleted = :deleted')
            ->setParameter('deleted', false)
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


        return $this->render(':dashboard/propsell:index.html.twig', [
            'pagination' => $pagination,
            'states' => $states,
            'areas' => $areas,
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
    /**
     * TODO Make super admin able to add house for agencies
     * @Route("/edit/{id}", name="propsell_edit")
     */
    public function editAction(House $house, Request $request)
    {
        $form = $this->createForm("AppBundle\Form\AdminHouseFormType", $house);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                $this->addFlash('success', 'Property updated');
                return $this->redirectToRoute('propsell_edit', array('id' => $house->getId()));
            }
        }
        return $this->render(':dashboard/propsell:edit.html.twig', [
            'house' => $house,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/add", name="propsell_add")
     */
    public function createAction(Request $request)
    {
        $house = new House();
        $form = $this->createForm("AppBundle\Form\AdminHouseFormType", $house);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $house->setAgency($this->getUser());
                $house->setSelling(true);
                $em = $this->getDoctrine()->getManager();
                $em->persist($house);
                $em->flush();
                $this->addFlash('success', 'Property added');
                return $this->redirectToRoute('propsell_index');
            }
        }

        return $this->render(':dashboard/propsell:create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * TODO make this only POST
     * @Route("/delete/{id}", name="propsell_delete")
     */
    public function deleteAction(House $house, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $house->setDeleted(true);
        $em->flush();
        $this->addFlash('success', 'Property moved to Recycle bin');
        return $this->redirectToRoute('propsell_index');
    }
    /**
     * TODO make this only POST
     * @Route("/enable/{id}", name="propsell_enable")
     */
    public function enableAction(House $house, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $house->setAvailable(true);
        $em->flush();
        $this->addFlash('success', 'Property made available');
        return $this->redirectToRoute('propsell_index');
    }
    /**
     * TODO make this only POST
     * @Route("/disable/{id}", name="propsell_disable")
     */
    public function disableAction(House $house, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $house->setAvailable(false);
        $em->flush();
        $this->addFlash('success', 'Property made unavailable');
        return $this->redirectToRoute('propsell_index');
    }
    /**
     * TODO make this only POST
     * @Route("/move/{id}", name="propsell_move")
     */
    public function moveAction(House $house, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $house->setSelling(false);
        $em->flush();
        $this->addFlash('success', 'Property moved to Properties for rent');
        return $this->redirectToRoute('propsell_index');
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
