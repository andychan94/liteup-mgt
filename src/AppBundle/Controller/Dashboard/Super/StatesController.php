<?php

namespace AppBundle\Controller\Dashboard\Super;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\State;
use Doctrine\DBAL\ConnectionException;
use Doctrine\ORM\EntityManager;
use Exception;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Route("/dashboard/admin/states")
 */
class StatesController extends BaseController
{
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->entityName = "State";
        $this->entityAltName = "states";
        $this->objName = State::class;
    }

    /**
     * @Route("/", name="states_index")
     * @param Request $request
     * @param LoggerInterface $logger
     * @return Response
     */
    public function indexAction(Request $request, LoggerInterface $logger)
    {
        $limit = (int)$request->query->get('limit');
        $perpage = (!is_null($limit) && $limit > 0) ? $limit : 20;
        try {
            $getAll = $this->getDoctrine()
                ->getRepository($this->objName)
                ->findAll();
            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $getAll,
                $request->query->getInt('page', 1),
                $perpage
            );

        } catch (Exception $e) {
            $this->addFlash(
                'error',
                $this->t('app.error')
            );
            $this->logger($logger, $e->getMessage());

            return $this->redirectToRoute('dashboard_home');
        }

        return $this->render(':dashboard/admin/' . $this->entityAltName . ':index.html.twig', [
            'pagination' => $pagination,
            'limit' => $perpage
        ]);
    }

    /**
     * @Route("/edit/{id}", name="states_edit")
     * @param State $entity
     * @param Request $request
     * @param LoggerInterface $logger
     * @return Response
     */
    public function editAction(State $entity, Request $request, LoggerInterface $logger)
    {
        $form = $this->createForm("AppBundle\Form\AdminStatesFormType", $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                /**
                 * @var $em EntityManager
                 */
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
            }
        }
        return $this->render(':dashboard/admin/' . $this->entityAltName . ':edit.html.twig', [
            'entity' => $entity,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/add", name="states_add_single")
     * @param Request $request
     * @param LoggerInterface $logger
     * @return Response
     */
    public function createAction(Request $request, LoggerInterface $logger)
    {
        $entity = new State();
        $form = $this->createForm("AppBundle\Form\AdminStatesFormType", $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var $em EntityManager
             */
            $em = $this->getDoctrine()->getManager();
            try {
                $em->persist($entity);
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
                $this->t('%entity%.added', array('%entity%' => $this->entityName))
            );
            return $this->redirectToRoute($this->entityAltName . '_index');
        }

        return $this->render(':dashboard/admin/' . $this->entityAltName . ':create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="state_delete_single")
     * @param State $entity
     * @param LoggerInterface $logger
     * @return RedirectResponse
     */
    public function deleteSingleAction(State $entity, LoggerInterface $logger)
    {
        /**
         * @var $em EntityManager
         */
        $em = $this->getDoctrine()->getManager();
        if ($entity != null) {
            try {
                $em->remove($entity);
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
                $this->t('entity.deleted')
            );
        }
        return $this->redirectToRoute($this->entityAltName . '_index');
    }

    /**
     * @Route("/delete", name="state_delete_many", methods={"POST"})
     * @param Request $request
     * @param LoggerInterface $logger
     * @return RedirectResponse
     */
    public function deleteManyAction(Request $request, LoggerInterface $logger)
    {
        if ($request != null) {
            $param = $request->request->get($this->entityAltName, null);
            if (!is_null($param)) {
                /**
                 * @var $em EntityManager
                 */
                $em = $this->getDoctrine()->getManager();
                foreach ($param as $id) {
                    $entity = $this->getDoctrine()
                        ->getRepository($this->objName)
                        ->find($id);
                    $em->remove($entity);
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
                        'entity.deleted_many',
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
     * @Route("/empty", name="state_empty", methods={"POST"})
     * @param Request $request
     * @param LoggerInterface $logger
     * @return RedirectResponse
     */
    public function emptyAction(Request $request, LoggerInterface $logger)
    {
        /**
         * @var $em EntityManager
         */
        $em = $this->getDoctrine()->getManager();
        $this->emptyTable($em, $logger);
        $this->addFlash(
            'success',
            $this->t(
                'entity.deleted_all',
                array(
                    '%entity%' => $this->entityName
                )
            )
        );
        return $this->redirectToRoute($this->entityAltName . '_index');
    }

    /**
     * @Route("/csv", name="state_csv", methods={"POST"})
     * @param Request $request
     * @param LoggerInterface $logger
     * @return RedirectResponse
     */
    public function csvAction(Request $request, LoggerInterface $logger)
    {
        if (isset($_FILES["csv"])) {
            $csv = array_map('str_getcsv', file($_FILES["csv"]["tmp_name"]));
            /**
             * @var $em EntityManager
             */
            $em = $this->getDoctrine()->getManager();
            if ($request->request->has('deleteAll')) {
                $this->emptyTable($em, $logger);
            }
            foreach ($csv as $value) {
                $entity = new State();
                try {
                    $entity->setName($value[0]);
                    $em->persist($entity);

                } catch (Exception $e) {
                    $this->addFlash('error', "An error occurred");
                    $logger->error($e->getMessage());
                    return $this->redirectToRoute($this->entityAltName . '_index');
                }
            }
            try {
                $em->flush();
            } catch (Exception $e) {
                $this->addFlash(
                    'error',
                    $this->t('app.error_upload')
                );
                $this->logger($logger, $e->getMessage(), "csv array:\n" . json_encode($csv));
                return $this->redirectToRoute($this->entityAltName . '_index');
            }
            $this->addFlash(
                'success',
                $this->t(
                    'entity.added_many',
                    array(
                        '%count%' => count($csv),
                        '%entity%' => $this->entityName
                    )
                )
            );
        } else {
            $this->addFlash(
                'error',
                $this->t('app.error_no_csv')
            );

        }
        return $this->redirectToRoute($this->entityAltName . '_index');
    }

    /**
     * @param EntityManager $em
     * @param LoggerInterface $logger
     */
    private function emptyTable(EntityManager $em, LoggerInterface $logger)
    {
        $classMetaData = $em->getClassMetadata($this->objName);
        $connection = $em->getConnection();
        $dbPlatform = $connection->getDatabasePlatform();
        $connection->beginTransaction();
        try {
            $connection->query('SET FOREIGN_KEY_CHECKS=0');
            $q = $dbPlatform->getTruncateTableSql($classMetaData->getTableName());
            $connection->executeUpdate($q);
            $connection->query('SET FOREIGN_KEY_CHECKS=1');
            $connection->commit();
        } catch (Exception $e) {
            try {
                $connection->rollback();
            } catch (ConnectionException $ce) {
                $this->addFlash(
                    'error',
                    $this->t('app.error')
                );
                $logger->error($e->getMessage());
            }
            $this->addFlash(
                'error',
                $this->t('app.error')
            );
            $logger->error($e->getMessage());
        }
    }
}