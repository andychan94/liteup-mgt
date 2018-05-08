<?php

namespace AppBundle\Controller\Dashboard;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\House;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;


/**
 * @Route("/dashboard")
 */
class PropTypeDeterminerController extends BaseController
{
    /**
     * @Route("/det/{id}", name="determiner")
     * @param House $house
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function determinerAction(House $house, Request $request)
    {
        try {
            if ($house->getSelling() === 1) {
                return $this->redirectToRoute('propsell_edit', array('id' => $house->getId()));
            }
            return $this->redirectToRoute('proprent_edit', array('id' => $house->getId()));
        } catch (Exception $e) {
            return $this->redirectToRoute('dashboard_home');
        }
    }
}
