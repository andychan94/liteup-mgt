<?php

namespace AppBundle\Controller\Dashboard;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\House;
use AppBundle\Entity\Lga;
use AppBundle\Entity\State;
use AppBundle\Form\AdminHouseFormType;
use Doctrine\ORM\EntityManager;
use Exception;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Route("/dashboard/property")
 */
class PropertyController extends BaseController
{
    /**
     * @var $objName string
     */
    private $objName = House::class;
    /**
     * @var $formTypeName string
     */
    private $formTypeName = AdminHouseFormType::class;

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->entityName = "Property";
        $this->entityAltName = "property";
        $this->entityAltNamePlu = "properties";
    }

    /**
     * @Route("/", name="property_index")
     * @param Request $request
     * @param LoggerInterface $logger
     * @return Response
     */
    public function indexAction(Request $request, LoggerInterface $logger)
    {
        $limit = (int)$request->query->get('limit');
        $perpage = (!is_null($limit) && $limit > 0) ? $limit : 20;
        $agency = $this->getUser();
        $queryBuilder = $this->getDoctrine()->getRepository($this->objName);
        $findParams = array(
            'isDeleted' => false
        );
        if (!in_array('ROLE_SUPER_ADMIN', $agency->getRoles())) {
            $findParams['agency'] = $agency;
        }
        try {
            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $queryBuilder->findBy($findParams, array('updatedAt' => 'DESC')),
                $request->query->getInt('page', 1),
                $perpage
            );
            $states = $this->getDoctrine()->getRepository(State::class)->findAll();
            $lgas = $this->getDoctrine()->getRepository(Lga::class)->findAll();
        } catch (Exception $e) {
            $this->addFlash(
                'error',
                $this->t('app.error')
            );
            $this->logger($logger, $e->getMessage());
            return $this->redirectToRoute('dashboard_home');
        }
        return $this->render(':dashboard/' . $this->entityAltName . ':index.html.twig', [
            'pagination' => $pagination,
            'states' => $states,
            'lgas' => $lgas,
            'limit' => $perpage,
            'entityAltName' => $this->entityAltName,
            'entityAltNamePlu' => $this->entityAltNamePlu,
            'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/edit/{id}/{prev}", defaults={"prev"="property"}, name="property_edit")
     * @param House $entity
     * @param Request $request
     * @param $prev
     * @param LoggerInterface $logger
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editAction(House $entity, Request $request, $prev, LoggerInterface $logger)
    {
        $houseError = "";
        $this->denyAccessUnlessGranted('edit', $entity);
        $form = $this->createForm($this->formTypeName, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $isShort = $form->get('isShort')->getData();
            $isShortPrice = (int)$form->get('priceShort')->getData();
            $isRent = $form->get('isRent')->getData();
            $isRentPrice = (int)$form->get('priceRent')->getData();
            $isBuy = $form->get('isBuy')->getData();
            $isBuyPrice = (int)$form->get('priceBuy')->getData();
            if (!$isShort && !$isRent && !$isBuy) {
                $houseError = "Please choose at least one option";
            } else {
                if ((!$isShort && $isShortPrice > 0) || (!$isRent && $isRentPrice > 0) || (!$isBuy && $isBuyPrice > 0)) {
                    $houseError = "Price error";
                } elseif (($isShort && $isShortPrice < 1) || ($isRent && $isRentPrice < 1) || ($isBuy && $isBuyPrice < 1)) {
                    $houseError = "Price error";
                } else {
                    if ($form->isValid()) {
                        $em = $this->getDoctrine()->getManager();
                        try {
                            $em->flush();
                        } catch (Exception $e) {
                            $this->addFlash(
                                'error',
                                $this->t('app.error')
                            );
                            $this->logger($logger, $e->getMessage());
                            return $this->redirectToRoute($prev . '_index');
                        }
                        $this->addFlash(
                            'success',
                            $this->t(
                                'entity.updated',
                                array('%entity%' => $this->entityName)
                            )
                        );
                        return $this->redirectToRoute($this->entityAltName . '_edit', array('id' => $entity->getId(), 'prev' => $prev));
                    }
                }
            }
        }
        $states = $this->getDoctrine()->getRepository(State::class)->findAll();
        return $this->render(':dashboard/' . $this->entityAltName . ':edit.html.twig', [
            'entity' => $entity,
            'form' => $form->createView(),
            'states' => $states,
            'entityAltName' => $this->entityAltName,
            'entityAltNamePlu' => $this->entityAltNamePlu,
            'error' => $houseError,
            'prev' => $prev
        ]);
    }

    /**
     * @Route("/add/{prev}", defaults={"prev"="property"}, name="property_add")
     */
    public function createAction(Request $request, $prev, LoggerInterface $logger,  \Swift_Mailer $mailer)
    {
        $house = new House();
        $houseError = "";
        $form = $this->createForm($this->formTypeName, $house);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $isShort = $form->get('isShort')->getData();
            $isShortPrice = (int)$form->get('priceShort')->getData();
            $isRent = $form->get('isRent')->getData();
            $isRentPrice = (int)$form->get('priceRent')->getData();
            $isBuy = $form->get('isBuy')->getData();
            $isBuyPrice = (int)$form->get('priceBuy')->getData();
            if (!$isShort && !$isRent && !$isBuy) {
                $houseError = "Please choose at least one option";
            } else {
                if ((!$isShort && $isShortPrice > 0) || (!$isRent && $isRentPrice > 0) || (!$isBuy && $isBuyPrice > 0)) {
                    $houseError = "Price error";
                } elseif (($isShort && $isShortPrice < 1) || ($isRent && $isRentPrice < 1) || ($isBuy && $isBuyPrice < 1)) {
                    $houseError = "Price error";
                } else {
                    if ($form->isValid()) {
                        $em = $this->getDoctrine()->getManager();
                        $house->setAgency($this->getUser());
                        $house->setIsDeleted(false);
                        $user = $this->getUser();
                        if  ($user->getUserPlan() != null && $user->getUserPlan()->getActive() == true){
                            $x = $em->getRepository('AppBundle:House');
                            $query = $x->createQueryBuilder('h')
                                ->select('count(h.id)')
                                ->where('h.agency = :agency')
                                ->andWhere('h.deactivate = :false')
                                ->setParameter('agency', $user)
                                ->setParameter('false', false)
                                ->getQuery()
                            ;
                            $count = $query->getSingleScalarResult();

                           if ($user->getUserPlan()->getPlan()->getId() == 1 && $count > 3){

                              $house->setDeactivate(true);

                           }else{

                               $house->setDeactivate(false);
                           }

                        }else{
                            $this->addFlash('error', 'Please subscribe to plan!');
                            return $this->redirectToRoute('property_add');
                        }

                        try {
                            $em->persist($house);
                            $em->flush();
                           $email = $em->getRepository('AppBundle:Email')->find(6);
                            $message = (new \Swift_Message($email->getEmailSubject()))
                                ->setFrom($this->getParameter('mailer_user'))
                                ->setTo($user->getEmail())
                                ->setBody(
                                    $this->renderView(
                                        'Emails/activation_email.html.twig',
                                        array('user' => $user->getName(),'text' => $email->getEmailText())
                                    ),
                                    'text/html'
                                )
                            ;
                            $mailer->send($message);
                        } catch (Exception $e) {
                            $this->addFlash(
                                'error',
                                $this->t('app.error')
                            );
                            $this->logger($logger, $e->getMessage());
                            return $this->redirectToRoute($prev . '_index');
                        }
                        $this->addFlash('success', 'Property added');
                        return $this->redirectToRoute('photo_add', array(
                            'id' => $house->getId(),
                            'prev' => $prev
                        ));
                    }
                }
            }
        }
        $states = $this->getDoctrine()->getRepository(State::class)->findAll();

        return $this->render(':dashboard/' . $this->entityAltName . ':create.html.twig', [
            'states' => $states,
            'entityAltName' => $this->entityAltName,
            'entityAltNamePlu' => $this->entityAltNamePlu,
            'form' => $form->createView(),
            'error' => $houseError,
            'prev' => $prev
        ]);
    }

    /**
     * @Route("/delete/{id}/{prev}", defaults={"prev"="property"}, name="property_delete")
     * @param House $entity
     * @param $prev
     * @param LoggerInterface $logger
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(House $entity, $prev, LoggerInterface $logger)
    {
        $this->denyAccessUnlessGranted('edit', $entity);
        $em = $this->getDoctrine()->getManager();
        if ($entity != null) {
            try {
                $entity->setIsDeleted(true);
                $em->flush();
            } catch (Exception $e) {
                $this->addFlash(
                    'error',
                    $this->t('app.error')
                );
                $this->logger($logger, $e->getMessage());
                return $this->redirectToRoute($prev . '_index');
            }
            $this->addFlash(
                'success',
                $this->t(
                    'entity.moved_to_deleted',
                    array(
                        '%count%' => 1,
                        '%entity%' => $this->entityName
                    )
                )
            );
        }
        return $this->redirectToRoute($prev . '_index');
    }

    /**
     * @Route("/multi-disabled/{prev}", defaults={"prev"="property"}, name="property_delete_many", methods={"POST"})
     */
    public function deleteManyAction(Request $request, $prev, LoggerInterface $logger)
    {

        if ($request != null) {
            $param = $request->request->get($this->entityAltNamePlu, null);
            if (!is_null($param)) {
                /**
                 * @var $em EntityManager
                 */
                $em = $this->getDoctrine()->getManager();
                foreach ($param as $id) {
                    $entity = $em
                        ->getRepository($this->objName)
                        ->find($id);
                    $this->denyAccessUnlessGranted('edit', $entity);
                    $entity->setIsDeleted(true);
                }
                try {
                    $em->flush();
                } catch (Exception $e) {
                    $this->addFlash(
                        'error',
                        $this->t('app.error')
                    );
                    $this->logger($logger, $e->getMessage());
                    return $this->redirectToRoute($prev . '_index');
                }
                $this->addFlash(
                    'success',
                    $this->t(
                        'entity.moved_to_deleted',
                        array(
                            '%count%' => count($param),
                            '%entity%' => $this->entityName
                        )
                    )
                );
            }
        }
        return $this->redirectToRoute($prev . '_index');
    }


    /**
     * @Route("/enable/{id}/{prev}", name="property_enable")
     * @param House $entity
     * @param $prev
     * @param LoggerInterface $logger
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function enableAction(House $entity, $prev, LoggerInterface $logger)
    {
        $this->denyAccessUnlessGranted('edit', $entity);
        $em = $this->getDoctrine()->getManager();
        if ($entity != null) {
            try {
                $entity->setIsAvailable(true);
                $em->flush();
            } catch (Exception $e) {
                $this->addFlash(
                    'error',
                    $this->t('app.error')
                );
                $this->logger($logger, $e->getMessage());
                return $this->redirectToRoute($prev . '_index');
            }
            $this->addFlash(
                'success',
                $this->t(
                    'entity.made_available',
                    array(
                        '%count%' => 1,
                        '%entity%' => $this->entityName
                    )
                )
            );
        }
        return $this->redirectToRoute($prev . '_index');
    }

    /**
     * @Route("/enable/{prev}", defaults={"prev"="property"}, name="property_enable_many")
     * @param Request $request
     * @param $prev
     * @param LoggerInterface $logger
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function enableManyAction(Request $request, $prev, LoggerInterface $logger)
    {
        if ($request != null) {
            $param = $request->request->get($this->entityAltNamePlu, null);
            if (!is_null($param)) {
                /**
                 * @var $em EntityManager
                 */
                $em = $this->getDoctrine()->getManager();
                foreach ($param as $id) {
                    $entity = $this->getDoctrine()
                        ->getRepository($this->objName)
                        ->find($id);
                    $this->denyAccessUnlessGranted('edit', $entity);
                    $entity->setIsAvailable(true);
                }
                try {
                    $em->flush();
                } catch (Exception $e) {
                    $this->addFlash(
                        'error',
                        $this->t('app.error')
                    );
                    $this->logger($logger, $e->getMessage());
                    return $this->redirectToRoute($prev . '_index');
                }
                $this->addFlash(
                    'success',
                    $this->t(
                        'entity.moved_to_deleted',
                        array(
                            '%count%' => count($param),
                            '%entity%' => $this->entityName
                        )
                    )
                );
            }
        }
        return $this->redirectToRoute($prev . '_index');
    }

    /**
     * @Route("/disable/{id}/{prev}", defaults={"prev"="property"}, name="property_disable")
     * @param House $entity
     * @param $prev
     * @param LoggerInterface $logger
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function disableAction(House $entity, $prev, LoggerInterface $logger)
    {
        $this->denyAccessUnlessGranted('edit', $entity);
        $em = $this->getDoctrine()->getManager();
        if ($entity != null) {
            try {
                $entity->setIsAvailable(false);
                $em->flush();
            } catch (Exception $e) {
                $this->addFlash(
                    'error',
                    $this->t('app.error')
                );
                $this->logger($logger, $e->getMessage());
                return $this->redirectToRoute($prev . '_index');
            }
            $this->addFlash(
                'success',
                $this->t(
                    'entity.made_unavailable',
                    array(
                        '%count%' => 1,
                        '%entity%' => $this->entityName
                    )
                )
            );
        }
        return $this->redirectToRoute($prev . '_index');
    }

    /**
     * @Route("/disable/{prev}", defaults={"prev"="property"}, name="property_disable_many")
     * @param Request $request
     * @param $prev
     * @param LoggerInterface $logger
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function disableManyAction(Request $request, $prev, LoggerInterface $logger)
    {
        if ($request != null) {
            $param = $request->request->get($this->entityAltNamePlu, null);
            if (!is_null($param)) {
                /**
                 * @var $em EntityManager
                 */
                $em = $this->getDoctrine()->getManager();
                foreach ($param as $id) {
                    $entity = $this->getDoctrine()
                        ->getRepository($this->objName)
                        ->find($id);
                    $this->denyAccessUnlessGranted('edit', $entity);
                    $entity->setIsAvailable(false);
                }
                try {
                    $em->flush();
                } catch (Exception $e) {
                    $this->addFlash(
                        'error',
                        $this->t('app.error')
                    );
                    $this->logger($logger, $e->getMessage());
                    return $this->redirectToRoute($prev . '_index');
                }
                $this->addFlash(
                    'success',
                    $this->t(
                        'entity.made_unavailable',
                        array(
                            '%count%' => count($param),
                            '%entity%' => $this->entityName
                        )
                    )
                );
            }
        }
        return $this->redirectToRoute($prev . '_index');
    }
}
