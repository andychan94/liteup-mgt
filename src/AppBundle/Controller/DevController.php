<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/22/2018
 * Time: 2:51 PM
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class DevController extends Controller
{
    /**
     * @Route("/dev")
     */
    public function devAction()
    {
        $em = $this->getDoctrine()->getManager();

        $newDate = new \DateTime('- 7 month');
        $arr = [];
        $num = 7;
        $index = 0;
        for ($i=1; $i <= 7; $i++){
            $x = $num-1;
        $pricingQuery = $em->createQuery("

            SELECT count(h.id), h.title, h.bedrooms, h.bathrooms, h.toilets,

           (SELECT COUNT(b.id) FROM AppBundle:HouseInspection b WHERE b.requestedAt BETWEEN :after AND :before AND b.house = h GROUP BY h.id) x,

            (SELECT COUNT(a.id)	FROM AppBundle:CallRequest a WHERE a.requestedAt BETWEEN :after AND :before AND a.house = h GROUP BY h.id) ar

            FROM AppBundle:House h WHERE h.kind = 'bungalow' GROUP BY h.id ORDER BY x DESC, ar DESC
        ");

        $pricingQuery
            ->setParameter('after', new \DateTime("- {$num} month"))
            ->setParameter('before', new \DateTime("- {$x} month"));

        $pricingContact = $pricingQuery->getResult();
            $arr['data'][$index] = $pricingContact;
            $arr['date'][$index] = new \DateTime("- {$x} month");
            $num--;
            $index++;
        }
        dump($arr);
        exit();
    }

}