<?php

namespace AppBundle\Controller\Dashboard;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\House;
use AppBundle\Entity\Lga;
use AppBundle\Entity\State;
use AppBundle\Enum\HouseTypeEnum;
use AppBundle\Form\AdminHouseFormType;
use Doctrine\ORM\EntityManager;
use Exception;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Route("/dashboard/proprent")
 */
class PropertyRentController extends BaseController
{
    /**
     * @var $objName string
     */
    private $objName = House::class;
    /**
     * @var $formTypeName string
     */
    private $formTypeName = AdminHouseFormType::class;

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->entityName = "Property";
        $this->entityAltName = "proprent";
        $this->entityAltNamePlu = "proprents";
    }

    /**
     * @Route("/", name="proprent_index")
     * @param Request $request
     * @param LoggerInterface $logger
     * @return Response
     */
    public function indexAction(Request $request, LoggerInterface $logger)
    {
        $limit = (int)$request->query->get('limit');
        $perpage = (!is_null($limit) && $limit > 0) ? $limit : 20;
        $agencyKey = 'u.agency';
        $agencyValue = $this->getUser();
//         if getUser() is super admin, show all houses from all agencies
        if (in_array('ROLE_SUPER_ADMIN', $this->getUser()->getRoles())) {
            $agencyKey = '1';
            $agencyValue = 1;
        }
        try {
            $queryBuilder = $this->getDoctrine()->getRepository($this->objName)->createQueryBuilder('u')
                ->where($agencyKey . ' = :agency')
                ->setParameter('agency', $agencyValue)
                ->andWhere('u.type = :type')
                ->setParameter('type', HouseTypeEnum::TYPE_RENT)
                ->andWhere('u.deleted = :deleted')
                ->setParameter('deleted', false)
                ->orderBy('u.createdAt', 'DESC');
            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $queryBuilder,
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
     * @Route("/edit/{id}", name="proprent_edit")
     * @param House $entity
     * @param Request $request
     * @param LoggerInterface $logger
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editAction(House $entity, Request $request, LoggerInterface $logger)
    {
        $form = $this->createForm($this->formTypeName, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
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
                    return $this->redirectToRoute($this->entityAltName . '_index');
                }
                $this->addFlash(
                    'success',
                    $this->t(
                        'entity.updated',
                        array('%entity%' => $this->entityName)
                    )
                );
                return $this->redirectToRoute($this->entityAltName . '_edit', array('id' => $entity->getId()));
            }
        }
        $states = $this->getDoctrine()->getRepository(State::class)->findAll();
        return $this->render(':dashboard/' . $this->entityAltName . ':edit.html.twig', [
            'entity' => $entity,
            'form' => $form->createView(),
            'states' => $states,
            'entityAltName' => $this->entityAltName,
            'entityAltNamePlu' => $this->entityAltNamePlu
        ]);
    }

    /**
     * @Route("/add", name="proprent_add")
     * @param Request $request
     * @param LoggerInterface $logger
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function createAction(Request $request, LoggerInterface $logger)
    {
        $house = new House();
        $form = $this->createForm($this->formTypeName, $house);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $house->setAgency($this->getUser());
                $house->setType(HouseTypeEnum::TYPE_RENT);
                $house->setDeleted(false);
                $em = $this->getDoctrine()->getManager();
                try {
                    $em->persist($house);
                    $em->flush();
                } catch (Exception $e) {
                    $this->addFlash(
                        'error',
                        $this->t('app.error')
                    );
                    $this->logger($logger, $e->getMessage());
                    return $this->redirectToRoute($this->entityAltName . '_index');
                }
                $this->addFlash('success', 'Property added');
                return $this->redirectToRoute('photo_add', array('id' => $house->getId()));
            }
        }
        $states = $this->getDoctrine()->getRepository(State::class)->findAll();

        return $this->render(':dashboard/' . $this->entityAltName . ':create.html.twig', [
            'states' => $states,
            'entityAltName' => $this->entityAltName,
            'entityAltNamePlu' => $this->entityAltNamePlu,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="proprent_delete")
     * @param House $entity
     * @param LoggerInterface $logger
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(House $entity, LoggerInterface $logger)
    {
        $em = $this->getDoctrine()->getManager();
        if ($entity != null) {
            try {
                $entity->setDeleted(true);
                $em->flush();
            } catch (Exception $e) {
                $this->addFlash(
                    'error',
                    $this->t('app.error')
                );
                $this->logger($logger, $e->getMessage());
                return $this->redirectToRoute($this->entityAltNamePlu . '_index');
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
        return $this->redirectToRoute($this->entityAltName . '_index');
    }

    /**
     * @Route("/delete", name="proprent_delete_many", methods={"POST"})
     * @param Request $request
     * @param LoggerInterface $logger
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteManyAction(Request $request, LoggerInterface $logger)
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
                    $entity->setDeleted(true);
                }
                try {
                    $em->flush();
                } catch (Exception $e) {
                    $this->addFlash(
                        'error',
                        $this->t('app.error')
                    );
                    $this->logger($logger, $e->getMessage());
                    return $this->redirectToRoute($this->entityAltName . '_index');
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
        return $this->redirectToRoute($this->entityAltName . '_index');
    }

    /**
     * @Route("/enable/{id}", name="proprent_enable")
     * @param House $entity
     * @param LoggerInterface $logger
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function enableAction(House $entity, LoggerInterface $logger)
    {
        $em = $this->getDoctrine()->getManager();
        if ($entity != null) {
            try {
                $entity->setAvailable(true);
                $em->flush();
            } catch (Exception $e) {
                $this->addFlash(
                    'error',
                    $this->t('app.error')
                );
                $this->logger($logger, $e->getMessage());
                return $this->redirectToRoute($this->entityAltNamePlu . '_index');
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
        return $this->redirectToRoute($this->entityAltName . '_index');
    }

    /**
     * @Route("/enable", name="proprent_enable_many")
     * @param Request $request
     * @param LoggerInterface $logger
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function enableManyAction(Request $request, LoggerInterface $logger)
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
                    $entity->setAvailable(true);
                }
                try {
                    $em->flush();
                } catch (Exception $e) {
                    $this->addFlash(
                        'error',
                        $this->t('app.error')
                    );
                    $this->logger($logger, $e->getMessage());
                    return $this->redirectToRoute($this->entityAltName . '_index');
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
        return $this->redirectToRoute($this->entityAltName . '_index');
    }

    /**
     * @Route("/disable/{id}", name="proprent_disable")
     * @param House $entity
     * @param LoggerInterface $logger
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function disableAction(House $entity, LoggerInterface $logger)
    {
        $em = $this->getDoctrine()->getManager();
        if ($entity != null) {
            try {
                $entity->setAvailable(false);
                $em->flush();
            } catch (Exception $e) {
                $this->addFlash(
                    'error',
                    $this->t('app.error')
                );
                $this->logger($logger, $e->getMessage());
                return $this->redirectToRoute($this->entityAltNamePlu . '_index');
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
        return $this->redirectToRoute($this->entityAltName . '_index');
    }

    /**
     * @Route("/disable", name="proprent_disable_many")
     * @param Request $request
     * @param LoggerInterface $logger
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function disableManyAction(Request $request, LoggerInterface $logger)
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
                    $entity->setAvailable(false);
                }
                try {
                    $em->flush();
                } catch (Exception $e) {
                    $this->addFlash(
                        'error',
                        $this->t('app.error')
                    );
                    $this->logger($logger, $e->getMessage());
                    return $this->redirectToRoute($this->entityAltName . '_index');
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
        return $this->redirectToRoute($this->entityAltName . '_index');
    }

//    /**
//     * TODO make this only POST
//     * @Route("/move/{id}", name="proprent_move")
//     */
//    public function moveAction(House $house, Request $request)
//    {
//        $em = $this->getDoctrine()->getManager();
//        $house->setSelling(true);
//        $em->flush();
//        $this->addFlash('success', 'Property moved to Properties for sale');
//        return $this->redirectToRoute('proprent_index');
//    }

////    TODO SPECIAL: Add Multiple from a csv file : instead of adding agencies manually, multiple agencies can be added at the same time using a csv file
//
//    /**
//     * @Route("/admin/agency/csv", name="csv_add_agencies")
//     */
//    public function csvAction(Request $request)
//    {
////        TODO csv file upload; handler (insert into DB)
//        return $this->render('default/index.html.twig', [
//            'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
//        ]);
//    }
}
