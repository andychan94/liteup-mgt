<?php

namespace AppBundle\Controller\Dashboard\Super;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\State;
use AppBundle\Form\AdminStatesFormType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Exception;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class BaseController
 * @package AppBundle\Controller
 * @Route("/dashboard/admin/states")
 */
class StateController extends BaseController
{
    /**
     * @var $objName string
     */
    private $objName = State::class;
    /**
     * @var $formTypeName string
     */
    private $formTypeName = AdminStatesFormType::class;

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->entityName = "State";
        $this->entityAltName = "state";
        $this->entityAltNamePlu = "states";
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

        return $this->render(':dashboard/admin/' . $this->entityAltNamePlu . ':index.html.twig', [
            'pagination' => $pagination,
            'limit' => $perpage,
            'entityAltName' => $this->entityAltName,
            'entityAltNamePlu' => $this->entityAltNamePlu
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
        $form = $this->createForm($this->formTypeName, $entity);
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
                    return $this->redirectToRoute($this->entityAltNamePlu . '_index');
                }
                $this->addFlash(
                    'success',
                    $this->t(
                        'entity.updated',
                        array('%entity%' => $this->entityName)
                    )
                );
                return $this->redirectToRoute($this->entityAltNamePlu . '_index');
            }
        }
        return $this->render(':dashboard/admin/' . $this->entityAltNamePlu . ':edit.html.twig', [
            'entity' => $entity,
            'form' => $form->createView(),
            'entityAltName' => $this->entityAltName,
            'entityAltNamePlu' => $this->entityAltNamePlu
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
        $form = $this->createForm($this->formTypeName, $entity);
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
                return $this->redirectToRoute($this->entityAltNamePlu . '_index');
            }
            $this->addFlash(
                'success',
                $this->t('entity.added', array('%entity%' => $this->entityName))
            );
            return $this->redirectToRoute($this->entityAltNamePlu . '_index');
        }

        return $this->render(':dashboard/admin/' . $this->entityAltNamePlu . ':create.html.twig', [
            'form' => $form->createView(),
            'entityAltName' => $this->entityAltName,
            'entityAltNamePlu' => $this->entityAltNamePlu
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
            } /** @noinspection PhpRedundantCatchClauseInspection */
            catch (ForeignKeyConstraintViolationException $e) {
                $this->addFlash(
                    'error',
                    $this->t('app.unique_constraint_error')
                );
                return $this->redirectToRoute($this->entityAltNamePlu . '_index');
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
                $this->t('entity.deleted', array('%entity%' => $this->entityName))
            );
        }
        return $this->redirectToRoute($this->entityAltNamePlu . '_index');
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
                    $em->remove($entity);
                }
                try {
                    $em->flush();
                } /** @noinspection PhpRedundantCatchClauseInspection */
                catch (ForeignKeyConstraintViolationException $e) {
                    $this->addFlash(
                        'error',
                        $this->t('app.unique_constraint_error')
                    );
                    return $this->redirectToRoute($this->entityAltNamePlu . '_index');
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
                        'entity.deleted_many',
                        array(
                            '%count%' => count($param),
                            '%entity%' => $this->entityName
                        )
                    )
                );
            }
        }
        return $this->redirectToRoute($this->entityAltNamePlu . '_index');
    }

    /**
     * @Route("/empty", name="state_empty", methods={"POST"})
     * @param LoggerInterface $logger
     * @return RedirectResponse
     */
    public function emptyAction(LoggerInterface $logger)
    {
        /**
         * @var $em EntityManager
         */
        $em = $this->getDoctrine()->getManager();
        if (!$this->emptyTable($em, $logger, $this->objName)) {
            return $this->redirectToRoute($this->entityAltNamePlu . '_index');
        }
        return $this->redirectToRoute($this->entityAltNamePlu . '_index');
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
                if (!$this->emptyTable($em, $logger, $this->objName)) {
                    return $this->redirectToRoute($this->entityAltNamePlu . '_index');
                }
            }
            foreach ($csv as $value) {
                $entity = new State();
                try {
                    $entity->setName($value[0]);
                    $em->persist($entity);

                } catch (Exception $e) {
                    $this->addFlash('error', "An error occurred");
                    $logger->error($e->getMessage());
                    return $this->redirectToRoute($this->entityAltNamePlu . '_index');
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
                return $this->redirectToRoute($this->entityAltNamePlu . '_index');
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
        return $this->redirectToRoute($this->entityAltNamePlu . '_index');
    }
}