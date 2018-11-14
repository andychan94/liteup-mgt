<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10/13/2018
 * Time: 6:23 PM
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AjaxController extends Controller
{

    /**
     * @Route("get-user-data-ajax", name="get_user_data_ajax")
     */
    public
    function ajaxUserAction(Request $request)
    {

        $user_id = $request->get('user');
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($user_id);

        return new JsonResponse(array(
            'name' => $user->getName(),
            'phone' => $user->getPhone(),
            'address' => $user->getAddress(),
        ));

    }

    /**
     * @Route("get-house-data-ajax", name="get_house_data_ajax")
     */
    public
    function ajaxHouseAction(Request $request)
    {

        $house_id = $request->get('house');
        $house = $this->getDoctrine()->getRepository('AppBundle:House')->find($house_id);

        return new JsonResponse(array(
            'title' => $house->getTitle(),
            'address' => $house->getAddress(),
            'desc' => $house->getDescription(),
        ));

    }

    /**
     * @Route("get-user-house-data-ajax", name="get_user_house_data_ajax")
     */
    public
    function ajaxUserHouseAction(Request $request)
    {

        $house_id = $request->get('house');
        $house = $this->getDoctrine()->getRepository('AppBundle:UserHouse')->find($house_id);

        return new JsonResponse(array(
            'title' => $house->getHouseTitle(),
            'address' => $house->getHouseAddress(),
            'desc' => $house->getHouseDescription(),
            'price' => $house->getHousePrice(),
        ));

    }


    /**
     * @Route("/get_property_title_ajax")
    */
    public function getPropertyAjax(Request $request)
    {
        $val = $request->get('$val');
        $houses = $this->getDoctrine()->getRepository('AppBundle:House');
        $query = $houses->createQueryBuilder('h')
            ->select('h.title')
            ->where('h.title LIKE :title')
            ->setParameter('title', $val."%")
            ->groupBy('h.title')
            ->orderBy('h.title', 'ASC')
            ->setMaxResults(20)
            ->getQuery();
        $title = $query->getArrayResult();
        return new JsonResponse($title);
    }

    /**
     * @Route("/check-notification")
     */
    public function checkNotificationAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id');
        $notification = $em->getRepository('AppBundle:Notification')->find($id);
        $notification->setIsCheck(true);
        $em->flush();
        return new JsonResponse(true);

    }


    /**
     * @Route("/get-lga")
     */
    public function getLgaAction(Request $request)
    {
        $state_id = $request->get('state_id');
        $state = $this->getDoctrine()->getRepository('AppBundle:State')->find($state_id);
        $lgaRepository = $this->getDoctrine()->getRepository('AppBundle:Lga');
        $query = $lgaRepository->createQueryBuilder('l')
            ->select('l')
            ->where('l.state = :state')
            ->setParameter('state', $state)
            ->orderBy('l.name', 'ASC')
            ->getQuery();
        $lga = $query->getArrayResult();

        return new JsonResponse($lga);
    }

    /**
     * @Route("/get-area")
     */
    public function getAreaAction(Request $request)
    {
        $lga_id = $request->get('lga_id');
        $lga = $this->getDoctrine()->getRepository('AppBundle:Lga')->find($lga_id);
        $lgaRepository = $this->getDoctrine()->getRepository('AppBundle:Area');
        $query = $lgaRepository->createQueryBuilder('a')
            ->select('a')
            ->where('a.lga = :lga')
            ->setParameter('lga', $lga)
            ->orderBy('a.name', 'ASC')
            ->getQuery();
        $area = $query->getArrayResult();

        return new JsonResponse($area);
    }


}