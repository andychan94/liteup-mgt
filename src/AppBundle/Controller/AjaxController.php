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
     * @Route("/get-property-title-ajax")
     */
    public function getPropertyAjax(Request $request){

        $val =  $request->get('val');
        $houses = $this->getDoctrine()->getRepository('AppBundle:House');
        $query = $houses->createQueryBuilder('h')
            ->select('h.title')
            ->where('h.title LIKE :title')
            ->setParameter('title', $val."%")
            ->groupBy('h.title')
            ->addOrderBy('h.title','ASC')
            ->addOrderBy('h.createdAt', 'DESC')
            ->getQuery();
        $title = $query->getResult();

        return new JsonResponse(json_encode($title));
    }

    /**
     * @Route("/check-notification")
     */
    public function checkNotificationAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id');
        $notification = $em->getRepository('AppBundle:Notification')->find($id);
        $notification->setIsCheck(true);
        $em->flush();
        return new JsonResponse(true);

    }


}