<?php

namespace AppBundle\Controller\Dashboard;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\House;
use AppBundle\Entity\Photo;
use Doctrine\ORM\EntityManager;
use Exception;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Route("/dashboard/photos/")
 */
class PhotoController extends BaseController
{
    /**
     * @var $objName string
     */
    private $objName = Photo::class;

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->entityName = "Photo";
        $this->entityAltName = "photo";
        $this->entityAltNamePlu = "photo";
    }

    /**
     * @Route("{id}/mgr/{prev}", defaults={"prev"="property"}, name="photo_index", methods={"GET"})
     * @param House $entity
     * @param $prev
     * @param Request $request
     * @return Response
     */
    public function photoManagerAction(House $entity, $prev, Request $request)
    {
        $this->denyAccessUnlessGranted('edit', $entity);
        $limit = (int)$request->query->get('limit');
        $perpage = (!is_null($limit) && $limit > 0) ? $limit : 20;
        $queryBuilder = $this->getDoctrine()->getRepository($this->objName)->createQueryBuilder('p')
            ->where('p.house = :house')
            ->setParameter('house', $entity->getId())
            ->orderBy('p.createdAt', 'DESC');
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            $perpage
        );

        return $this->render(':dashboard/photo:photo-mgr.html.twig', [
            'house' => $entity,
            'pagination' => $pagination,
            'entityAltName' => $this->entityAltName,
            'entityAltNamePlu' => $this->entityAltNamePlu,
            'prev' => $prev,
            'limit' => $perpage
        ]);
    }

    /**
     * @Route("{id}/upload/{prev}", defaults={"prev"="property"}, name="photo_add")
     * @param House $entity
     * @param $prev
     * @return Response
     */
    public function photoUploadAction(House $entity, $prev)
    {
        $this->denyAccessUnlessGranted('edit', $entity);
        return $this->render(':dashboard/photo:photo-upload.html.twig', [
            'house' => $entity,
            'entityAltName' => $this->entityAltName,
            'entityAltNamePlu' => $this->entityAltNamePlu,
            'prev' => $prev,
        ]);
    }

    /**
     * @Route("{id}/delete/{photo_id}/{prev}", defaults={"prev"="property"}, name="photo_delete_single")
     * @param $id
     * @param $photo_id
     * @param $prev
     * @param LoggerInterface $logger
     * @return RedirectResponse
     */
    public function deleteSingleAction($id, $photo_id, $prev, LoggerInterface $logger)
    {
        /**
         * @var $em EntityManager
         */
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository(Photo::class)->find($photo_id);
        if ($entity != null) {
            $house = $entity->getHouse();
            $this->denyAccessUnlessGranted('edit', $house);
            try {
                $filename = $this->get('kernel')->getRootDir() . '/../public_html' . $this->getParameter('uploads_folder') . $entity->getPath();
                $filesystem = new Filesystem();

                $filesystem->remove($filename);
                $em->remove($entity);
                $em->flush();
            } catch (Exception $e) {
                $this->addFlash(
                    'error',
                    $this->t('app.error')
                );
                $this->logger($logger, $e->getMessage());
                return $this->redirectToRoute($this->entityAltName . '_index', array('id' => $id, 'prev' => $prev));
            }
            $this->addFlash(
                'success',
                $this->t('entity.deleted', array('%entity%' => $this->entityName))
            );
        }
        return $this->redirectToRoute($this->entityAltName . '_index', array('id' => $id, 'prev' => $prev));
    }

    /**
     * @Route("{id}/mgr/{prev}", defaults={"prev"="property"}, name="photo_delete_many", methods={"POST"})
     * @param $id
     * @param Request $request
     * @param $prev
     * @param LoggerInterface $logger
     * @return RedirectResponse
     */
    public function deleteManyAction($id, $prev, Request $request, LoggerInterface $logger)
    {
        $em = $this->getDoctrine()->getManager();
        if ($request != null) {
            $param = $request->request->get($this->entityAltNamePlu, null);
            if (!is_null($param)) {
                /**
                 * @var $em EntityManager
                 */
                $filesystem = new Filesystem();
                foreach ($param as $val) {
                    $entity = $this->getDoctrine()
                        ->getRepository($this->objName)
                        ->find($val);
                    $house = $entity->getHouse();
                    $this->denyAccessUnlessGranted('edit', $house);
                    $filename = $this->getParameter('uploads_folder') . $entity->getPath();
                    try {
                        $filesystem->remove($filename);
                    } catch (Exception $e) {
                        $this->addFlash(
                            'error',
                            $this->t('app.error')
                        );
                        $this->logger($logger, $e->getMessage());
                        return $this->redirectToRoute($this->entityAltName . '_index', array('id' => $id, 'prev' => $prev));
                    }
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
                    return $this->redirectToRoute($this->entityAltName . '_index', array('id' => $id, 'prev' => $prev));
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
        return $this->redirectToRoute($this->entityAltName . '_index', array('id' => $id, 'prev' => $prev));
    }
}
