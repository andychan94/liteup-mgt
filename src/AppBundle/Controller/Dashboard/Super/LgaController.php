<?php

namespace AppBundle\Controller\Dashboard\Super;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Lga;
use AppBundle\Entity\State;
use AppBundle\Form\AdminLgasFormType;
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
 * @Route("/dashboard/admin/lgas")
 */
class LgaController extends BaseController
{
    /**
     * @var $objName string
     */
    private $objName = Lga::class;
    /**
     * @var $formTypeName string
     */
    private $formTypeName = AdminLgasFormType::class;

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->entityName = "Lga";
        $this->entityAltName = "lga";
        $this->entityAltNamePlu = "lgas";
    }

    /**
     * @Route("/", name="lgas_index")
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
     * @Route("/edit/{id}", name="lgas_edit")
     * @param Lga $entity
     * @param Request $request
     * @param LoggerInterface $logger
     * @return Response
     */
    public function editAction(Lga $entity, Request $request, LoggerInterface $logger)
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
     * @Route("/add", name="lgas_add_single")
     * @param Request $request
     * @param LoggerInterface $logger
     * @return Response
     */
    public function createAction(Request $request, LoggerInterface $logger)
    {
        $entity = new Lga();
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
     * @Route("/delete/{id}", name="lga_delete_single")
     * @param Lga $entity
     * @param LoggerInterface $logger
     * @return RedirectResponse
     */
    public function deleteSingleAction(Lga $entity, LoggerInterface $logger)
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
     * @Route("/delete", name="lga_delete_many", methods={"POST"})
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
     * @Route("/empty", name="lga_empty", methods={"POST"})
     * @param LoggerInterface $logger
     * @return RedirectResponse
     */
    public function emptyAction(LoggerInterface $logger)
    {
        /**
         * @var $em EntityManager
         */
        $em = $this->getDoctrine()->getManager();
        $this->emptyTable($em, $logger, $this->objName);
        return $this->redirectToRoute($this->entityAltNamePlu . '_index');
    }

    /**
     * @Route("/csv", name="lga_csv", methods={"POST"})
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
                $this->emptyTable($em, $logger, $this->objName);
            }
            foreach ($csv as $value) {
                $entity = new Lga();
                try {
                    $entity->setName($value[1]);
                    $parent = $this->getDoctrine()->getRepository(State::class)->find($value[0]);
                    $entity->setState($parent);
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