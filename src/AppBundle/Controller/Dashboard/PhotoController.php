<?php

namespace AppBundle\Controller\Dashboard;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Area;
use AppBundle\Entity\House;
use AppBundle\Entity\Lga;
use AppBundle\Entity\Photo;
use AppBundle\Entity\State;
use AppBundle\Form\AdminHouseFormType;
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

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->entityName = "Photo";
        $this->entityAltName = "photo";
        $this->entityAltNamePlu = "photo";
    }

    /**
     * @Route("{id}/", name="photo_index")
     * @param House $house
     * @param Request $request
     * @return Response
     */
    public function photoManagerAction(House $house, Request $request)
    {
        $limit = (int)$request->query->get('limit');
        $perpage = (!is_null($limit) && $limit > 0) ? $limit : 20;
        $queryBuilder = $this->getDoctrine()->getRepository($this->objName)->createQueryBuilder('p')
            ->where('p.house = :house')
            ->setParameter('house', $house->getId())
            ->orderBy('p.createdAt', 'DESC');
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            $perpage
        );

        return $this->render(':dashboard/photo:photo-mgr.html.twig', [
            'house' => $house,
            'pagination' => $pagination,
            'entityAltName' => $this->entityAltName,
            'entityAltNamePlu' => $this->entityAltNamePlu,
            'limit' => $perpage
        ]);
    }

    /**
     * @Route("{id}/upload", name="photo_add")
     * @param House $house
     * @return Response
     */
    public function photoUploadAction(House $house)
    {
        return $this->render(':dashboard/photo:photo-upload.html.twig', [
            'house' => $house,
            'entityAltName' => $this->entityAltName,
            'entityAltNamePlu' => $this->entityAltNamePlu,
        ]);
    }

    /**
     * @Route("{id}/delete/{photo_id}", name="photo_delete_single")
     * @param $id
     * @param $photo_id
     * @param LoggerInterface $logger
     * @return RedirectResponse
     */
    public function deleteSingleAction($id, $photo_id, LoggerInterface $logger)
    {
        /**
         * @var $em EntityManager
         */
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository(Photo::class)->find($photo_id);
        if ($entity != null) {
            try {
                $filename = $this->getParameter('uploads_folder') . $entity->getPath();
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
                return $this->redirectToRoute($this->entityAltName . '_index', array('id' => $id));
            }
            $this->addFlash(
                'success',
                $this->t('entity.deleted', array('%entity%' => $this->entityName))
            );
        }
        return $this->redirectToRoute($this->entityAltName . '_index', array('id' => $id));
    }

    /**
     * @Route("{id}/delete", name="photo_delete_many", methods={"POST"})
     * @param Request $request
     * @param LoggerInterface $logger
     * @return RedirectResponse
     */
    public function deleteManyAction($id, Request $request, LoggerInterface $logger)
    {
        if ($request != null) {
            $param = $request->request->get($this->entityAltNamePlu, null);
            if (!is_null($param)) {
                /**
                 * @var $em EntityManager
                 */
                $em = $this->getDoctrine()->getManager();
                $filesystem = new Filesystem();
                foreach ($param as $val) {
                    $entity = $this->getDoctrine()
                        ->getRepository($this->objName)
                        ->find($val);
                    $filename = $this->getParameter('uploads_folder') . $entity->getPath();
                    try {
                        $filesystem->remove($filename);
                    } catch (Exception $e) {
                        $this->addFlash(
                            'error',
                            $this->t('app.error')
                        );
                        $this->logger($logger, $e->getMessage());
                        return $this->redirectToRoute($this->entityAltName . '_index', array('id' => $id));
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
                    return $this->redirectToRoute($this->entityAltName . '_index', array('id' => $id));
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
        return $this->redirectToRoute($this->entityAltName . '_index', array('id' => $id));
    }
}
