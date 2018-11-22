<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/21/2018
 * Time: 2:39 PM
 */

namespace AppBundle\Controller\Dashboard;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SalesAnalysisController extends Controller
{
    /**
     * @Route("/sales-advice", name="sales_advice")
     */
    public function salesAdviceAction(){

        $em = $this->getDoctrine()->getManager();
        $housesRepository = $this->getDoctrine()->getRepository('AppBundle:HouseInspection');
        $query = $em->createQuery("SELECT DISTINCT a.name,

                (SELECT count(i.id) FROM AppBundle:HouseInspection i WHERE  i.availableType = :Buy  AND i.house = h GROUP BY h.area) inspectBuy,
                (SELECT count(c.id) FROM AppBundle:CallRequest c WHERE  c.availableType = :Buy  AND c.house = h GROUP BY h.area) CallBuy,
                (SELECT count(f.id) FROM AppBundle:HouseInspection f WHERE  f.availableType = :Rent  AND f.house = h GROUP BY h.area) inspectRent,
                (SELECT count(d.id) FROM AppBundle:CallRequest d WHERE  d.availableType = :Rent  AND d.house = h GROUP BY h.area) CallRent,
                (SELECT count(j.id) FROM AppBundle:HouseInspection j WHERE  j.availableType = :Short  AND j.house = h GROUP BY h.area) inspectShort,
                (SELECT count(m.id) FROM AppBundle:CallRequest m WHERE  m.availableType = :Short  AND m.house = h GROUP BY h.area) CallShort

                FROM AppBundle:Area a

                JOIN AppBundle:House h WHERE h.area = a.id AND h.area is not null
         ");

        $query
            ->setParameter('Buy', 'Buy')
            ->setParameter('Rent', 'Rent')
            ->setParameter('Short', 'Short')
        ;

        $house = $query->getResult();

        return $this->render('dashboard/sales_analyses/sales_advice.html.twig',
            array(
                'sales' => $house,
            ));
    }

    /**
     * @Route("/room-count-contact-analysis", name="room_count_contact_analysis")
     */
    public function roomCountContactAction(){

        $em = $this->getDoctrine()->getManager();
        $housesRepository = $this->getDoctrine()->getRepository('AppBundle:HouseInspection');
        $query = $em->createQuery("SELECT DISTINCT h.bedrooms,
                                  (SELECT count(i.id) FROM AppBundle:HouseInspection   i WHERE  i.availableType = :Buy    AND  i.house = h) inspectBuy,
                                  (SELECT count(c.id) FROM AppBundle:CallRequest       c WHERE  c.availableType = :Buy    AND  c.house = h) CallBuy,
                                  (SELECT count(f.id) FROM AppBundle:HouseInspection   f WHERE  f.availableType = :Rent   AND  f.house = h) inspectRent,
                                  (SELECT count(d.id) FROM AppBundle:CallRequest       d WHERE  d.availableType = :Rent   AND  d.house = h) CallRent,
                                  (SELECT count(j.id) FROM AppBundle:HouseInspection   j WHERE  j.availableType = :Short  AND  j.house = h) inspectShort,
                                  (SELECT count(m.id) FROM AppBundle:CallRequest       m WHERE  m.availableType = :Short  AND  m.house = h) CallShort
                                  FROM AppBundle:House h WHERE h.bedrooms BETWEEN 0 AND 7
                                 ");
        $query
            ->setParameter('Buy', 'Buy')
            ->setParameter('Rent', 'Rent')
            ->setParameter('Short', 'Short')
        ;

        $house = $query->getResult();
        $arr = [];
        $index = 0;
        foreach ($house as $item) {
            foreach ($house as $rep => $val){
                if ($item['bedrooms'] == $val['bedrooms']){
                    $arr[$index]['bedrooms'] = $val['bedrooms'];
                    $arr[$index]['inspectBuy'] = $item['inspectBuy'] + $val['inspectBuy'];
                    $arr[$index]['CallBuy'] = $item['CallBuy'] +  $val['CallBuy'];
                    $arr[$index]['inspectRent'] = $item['inspectRent'] + $val['inspectRent'];
                    $arr[$index]['CallRent'] = $item['CallRent'] + $val['CallRent'];
                    $arr[$index]['inspectShort'] = $item['inspectShort'] + $val['inspectShort'];
                    $arr[$index]['CallShort'] = $item['CallShort'] + $val['CallShort'];
                    unset($house[$rep]);
                }else{
                    $arr[$index]['bedrooms'] = $item['bedrooms'];
                    $arr[$index]['inspectBuy'] = $item['inspectBuy'];
                    $arr[$index]['CallBuy'] = $item['CallBuy'];
                    $arr[$index]['inspectRent'] = $item['inspectRent'];
                    $arr[$index]['CallRent'] = $item['CallRent'];
                    $arr[$index]['inspectShort'] = $item['inspectShort'];
                    $arr[$index]['CallShort'] = $item['CallShort'];
                }
            }
            $index ++;
        }


        $query2 = $em->createQuery("SELECT DISTINCT h.bathrooms,
                                  (SELECT count(i.id) FROM AppBundle:HouseInspection   i WHERE  i.availableType = :Buy    AND  i.house = h) inspectBuy,
                                  (SELECT count(c.id) FROM AppBundle:CallRequest       c WHERE  c.availableType = :Buy    AND  c.house = h) CallBuy,
                                  (SELECT count(f.id) FROM AppBundle:HouseInspection   f WHERE  f.availableType = :Rent   AND  f.house = h) inspectRent,
                                  (SELECT count(d.id) FROM AppBundle:CallRequest       d WHERE  d.availableType = :Rent   AND  d.house = h) CallRent,
                                  (SELECT count(j.id) FROM AppBundle:HouseInspection   j WHERE  j.availableType = :Short  AND  j.house = h) inspectShort,
                                  (SELECT count(m.id) FROM AppBundle:CallRequest       m WHERE  m.availableType = :Short  AND  m.house = h) CallShort
                                  FROM AppBundle:House h WHERE h.bathrooms BETWEEN 0 AND 7
                                 ");
        $query2
            ->setParameter('Buy', 'Buy')
            ->setParameter('Rent', 'Rent')
            ->setParameter('Short', 'Short')
        ;

        $house2 = $query2->getResult();
        $arr2 = [];
        $index = 0;
        foreach ($house2 as $item) {
            foreach ($house2 as $rep => $val){
                if ($item['bathrooms'] == $val['bathrooms']){
                   $arr2[$index]['bathrooms'] = $val['bathrooms'];
                   $arr2[$index]['inspectBuy'] = $item['inspectBuy'] + $val['inspectBuy'];
                   $arr2[$index]['CallBuy'] = $item['CallBuy'] +  $val['CallBuy'];
                   $arr2[$index]['inspectRent'] = $item['inspectRent'] + $val['inspectRent'];
                   $arr2[$index]['CallRent'] = $item['CallRent'] + $val['CallRent'];
                   $arr2[$index]['inspectShort'] = $item['inspectShort'] + $val['inspectShort'];
                   $arr2[$index]['CallShort'] = $item['CallShort'] + $val['CallShort'];
                    unset($house2[$rep]);
                }else{
                    $arr2[$index]['bathrooms'] = $item['bathrooms'];
                    $arr2[$index]['inspectBuy'] = $item['inspectBuy'];
                    $arr2[$index]['CallBuy'] = $item['CallBuy'];
                    $arr2[$index]['inspectRent'] = $item['inspectRent'];
                    $arr2[$index]['CallRent'] = $item['CallRent'];
                    $arr2[$index]['inspectShort'] = $item['inspectShort'];
                    $arr2[$index]['CallShort'] = $item['CallShort'];
                }
            }
            $index ++;
        }


        $query3 = $em->createQuery("SELECT DISTINCT h.toilets,
                                  (SELECT count(i.id) FROM AppBundle:HouseInspection   i WHERE  i.availableType = :Buy    AND  i.house = h) inspectBuy,
                                  (SELECT count(c.id) FROM AppBundle:CallRequest       c WHERE  c.availableType = :Buy    AND  c.house = h) CallBuy,
                                  (SELECT count(f.id) FROM AppBundle:HouseInspection   f WHERE  f.availableType = :Rent   AND  f.house = h) inspectRent,
                                  (SELECT count(d.id) FROM AppBundle:CallRequest       d WHERE  d.availableType = :Rent   AND  d.house = h) CallRent,
                                  (SELECT count(j.id) FROM AppBundle:HouseInspection   j WHERE  j.availableType = :Short  AND  j.house = h) inspectShort,
                                  (SELECT count(m.id) FROM AppBundle:CallRequest       m WHERE  m.availableType = :Short  AND  m.house = h) CallShort
                                  FROM AppBundle:House h WHERE h.toilets BETWEEN 0 AND 7
                                 ");
        $query3
            ->setParameter('Buy', 'Buy')
            ->setParameter('Rent', 'Rent')
            ->setParameter('Short', 'Short')
        ;

        $house3 = $query3->getResult();
        $arr3 = [];
        $index = 0;
        foreach ($house3 as $item) {
            foreach ($house3 as $rep => $val){
                if ($item['toilets'] == $val['toilets']){
                    $arr3[$index]['toilets'] = $val['toilets'];
                    $arr3[$index]['inspectBuy'] = $item['inspectBuy'] + $val['inspectBuy'];
                    $arr3[$index]['CallBuy'] = $item['CallBuy'] +  $val['CallBuy'];
                    $arr3[$index]['inspectRent'] = $item['inspectRent'] + $val['inspectRent'];
                    $arr3[$index]['CallRent'] = $item['CallRent'] + $val['CallRent'];
                    $arr3[$index]['inspectShort'] = $item['inspectShort'] + $val['inspectShort'];
                    $arr3[$index]['CallShort'] = $item['CallShort'] + $val['CallShort'];
                    unset($house3[$rep]);
                }else{
                    $arr3[$index]['toilets'] = $item['toilets'];
                    $arr3[$index]['inspectBuy'] = $item['inspectBuy'];
                    $arr3[$index]['CallBuy'] = $item['CallBuy'];
                    $arr3[$index]['inspectRent'] = $item['inspectRent'];
                    $arr3[$index]['CallRent'] = $item['CallRent'];
                    $arr3[$index]['inspectShort'] = $item['inspectShort'];
                    $arr3[$index]['CallShort'] = $item['CallShort'];
                }
            }
            $index ++;
        }

        return $this->render('dashboard/sales_analyses/room_count_contact_analysis.html.twig',
            array(
                'analysis' => $arr,
                'bathrooms' => $arr2,
                'toilets' => $arr3,
            ));
    }
}