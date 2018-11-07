<?php

namespace AppBundle\Controller\Dashboard;

use AppBundle\Controller\BaseController;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Route("/dashboard/history")
 */
class HistoryController extends BaseController
{
//    /**
//     * @var $objName string
//     */
//    private $objName = House::class;

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->entityName = "History";
        $this->entityAltName = "history";
        $this->entityAltNamePlu = "histories";
    }

    /**
     * TODO implement contact/meet history
     * @Route("/", name="history_index")
     * @param Request $request
     * @param LoggerInterface $logger
     * @return Response
     */
    public function indexAction(Request $request, LoggerInterface $logger)
    {
        return $this->render(':dashboard/' . $this->entityAltName . ':index.html.twig', [
            'entityAltName' => $this->entityAltName,
            'entityAltNamePlu' => $this->entityAltNamePlu,
            'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
        ]);
    }

}
