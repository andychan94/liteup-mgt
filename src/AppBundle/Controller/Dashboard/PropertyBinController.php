<?php

namespace AppBundle\Controller\Dashboard;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\House;
use AppBundle\Entity\Lga;
use AppBundle\Entity\State;
use Exception;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Route("/dashboard/protbin")
 */
class PropertyBinController extends BaseController
{
    /**
     * @var $objName string
     */
    private $objName = House::class;

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->entityName = "Property";
        $this->entityAltName = "proptbin";
        $this->entityAltNamePlu = "proptbins";
    }

    /**
     * @Route("/", name="proptbin_index")
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
            'isDeleted' => true
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
     * @Route("/delete/{id}", name="property_remove")
     * @param House $entity
     * @param LoggerInterface $logger
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(House $entity, LoggerInterface $logger)
    {
        $em = $this->getDoctrine()->getManager();
        $this->denyAccessUnlessGranted('edit', $entity);
        if ($entity != null) {
            try {
                $photos = $entity->getPhotos()->getValues();
                /* @var $photoPath \AppBundle\Entity\Photo */
                foreach ($photos as $key=>$photoPath) {
                    $filesystem = new Filesystem();
                    $filesystem->remove($this->get('kernel')->getRootDir().'/../public_html'.$this->getParameter('uploads_folder') . $photoPath->getPath());
                }
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
                $this->t(
                    'entity.removed',
                    array(
                        '%count%' => 1,
                        '%entity%' => $this->entityName
                    )
                )
            );
        }
        return $this->redirectToRoute($this->entityAltName . '_index');
    }
}
