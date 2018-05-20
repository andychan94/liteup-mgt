<?php

namespace AppBundle\Controller\Dashboard\Super;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Agency;
use AppBundle\Entity\House;
use AppBundle\Entity\Lga;
use AppBundle\Entity\State;
use AppBundle\Form\AdminHouseFormType;
use Doctrine\ORM\EntityManager;
use Exception;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class BaseController
 * @package AppBundle\Controller
 * @Route("/dashboard/admin/agency")
 */
class AgencyController extends BaseController
{
    /**
     * @var $objName string
     */
    private $objName = Agency::class;

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->entityName = "Agency";
        $this->entityAltName = "agency";
        $this->entityAltNamePlu = "agencies";
    }

    /**
     * @Route("/", name="agency_index")
     * @param Request $request
     * @param LoggerInterface $logger
     * @return Response
     */
    public function indexAction(Request $request, LoggerInterface $logger)
    {
        $limit = (int)$request->query->get('limit');
        $perpage = (!is_null($limit) && $limit > 0) ? $limit : 20;
        $queryBuilder = $this->getDoctrine()->getRepository($this->objName);
        $findParams = array(
        );
        try {
            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $queryBuilder->findBy($findParams, array('updatedAt' => 'DESC')),
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
            'limit' => $perpage,
            'entityAltName' => $this->entityAltName,
            'entityAltNamePlu' => $this->entityAltNamePlu,
            'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
        ]);
    }
}
