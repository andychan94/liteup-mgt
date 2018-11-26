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
    public function salesAdviceAction()
    {

        $em = $this->getDoctrine()->getManager();

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
            ->setParameter('Short', 'Short');

        $house = $query->getResult();

        $bedroomsQuery = $em->createQuery("SELECT DISTINCT h.bedrooms,
                                  (SELECT count(i.id) FROM AppBundle:HouseInspection   i WHERE  i.availableType = :Buy    AND  i.house = h) inspectBuy,
                                  (SELECT count(c.id) FROM AppBundle:CallRequest       c WHERE  c.availableType = :Buy    AND  c.house = h) CallBuy,
                                  (SELECT count(f.id) FROM AppBundle:HouseInspection   f WHERE  f.availableType = :Rent   AND  f.house = h) inspectRent,
                                  (SELECT count(d.id) FROM AppBundle:CallRequest       d WHERE  d.availableType = :Rent   AND  d.house = h) CallRent,
                                  (SELECT count(j.id) FROM AppBundle:HouseInspection   j WHERE  j.availableType = :Short  AND  j.house = h) inspectShort,
                                  (SELECT count(m.id) FROM AppBundle:CallRequest       m WHERE  m.availableType = :Short  AND  m.house = h) CallShort
                                  FROM AppBundle:House h WHERE h.bedrooms BETWEEN 0 AND 7
                                 ");
        $bedroomsQuery
            ->setParameter('Buy', 'Buy')
            ->setParameter('Rent', 'Rent')
            ->setParameter('Short', 'Short');

        $bedrooms = $bedroomsQuery->getResult();
        $bedroomsArray = [];
        $index = 0;
        foreach ($bedrooms as $item) {
            foreach ($bedrooms as $rep => $val) {
                if ($item['bedrooms'] == $val['bedrooms']) {
                    $bedroomsArray[$index]['bedrooms'] = $val['bedrooms'];
                    $bedroomsArray[$index]['inspectBuy'] = $item['inspectBuy'] + $val['inspectBuy'];
                    $bedroomsArray[$index]['CallBuy'] = $item['CallBuy'] + $val['CallBuy'];
                    $bedroomsArray[$index]['inspectRent'] = $item['inspectRent'] + $val['inspectRent'];
                    $bedroomsArray[$index]['CallRent'] = $item['CallRent'] + $val['CallRent'];
                    $bedroomsArray[$index]['inspectShort'] = $item['inspectShort'] + $val['inspectShort'];
                    $bedroomsArray[$index]['CallShort'] = $item['CallShort'] + $val['CallShort'];
                    unset($bedrooms[$rep]);
                } else {
                    $bedroomsArray[$index]['bedrooms'] = $item['bedrooms'];
                    $bedroomsArray[$index]['inspectBuy'] = $item['inspectBuy'];
                    $bedroomsArray[$index]['CallBuy'] = $item['CallBuy'];
                    $bedroomsArray[$index]['inspectRent'] = $item['inspectRent'];
                    $bedroomsArray[$index]['CallRent'] = $item['CallRent'];
                    $bedroomsArray[$index]['inspectShort'] = $item['inspectShort'];
                    $bedroomsArray[$index]['CallShort'] = $item['CallShort'];
                }
            }
            $index++;
        }


        $bathroomsQuery = $em->createQuery("SELECT DISTINCT h.bathrooms,
                                  (SELECT count(i.id) FROM AppBundle:HouseInspection   i WHERE  i.availableType = :Buy    AND  i.house = h) inspectBuy,
                                  (SELECT count(c.id) FROM AppBundle:CallRequest       c WHERE  c.availableType = :Buy    AND  c.house = h) CallBuy,
                                  (SELECT count(f.id) FROM AppBundle:HouseInspection   f WHERE  f.availableType = :Rent   AND  f.house = h) inspectRent,
                                  (SELECT count(d.id) FROM AppBundle:CallRequest       d WHERE  d.availableType = :Rent   AND  d.house = h) CallRent,
                                  (SELECT count(j.id) FROM AppBundle:HouseInspection   j WHERE  j.availableType = :Short  AND  j.house = h) inspectShort,
                                  (SELECT count(m.id) FROM AppBundle:CallRequest       m WHERE  m.availableType = :Short  AND  m.house = h) CallShort
                                  FROM AppBundle:House h WHERE h.bathrooms BETWEEN 0 AND 7
                                 ");
        $bathroomsQuery
            ->setParameter('Buy', 'Buy')
            ->setParameter('Rent', 'Rent')
            ->setParameter('Short', 'Short');

        $bathrooms = $bathroomsQuery->getResult();
        $bathroomsArray = [];
        $index = 0;
        foreach ($bathrooms as $item) {
            foreach ($bathrooms as $rep => $val) {
                if ($item['bathrooms'] == $val['bathrooms']) {
                    $bathroomsArray[$index]['bathrooms'] = $val['bathrooms'];
                    $bathroomsArray[$index]['inspectBuy'] = $item['inspectBuy'] + $val['inspectBuy'];
                    $bathroomsArray[$index]['CallBuy'] = $item['CallBuy'] + $val['CallBuy'];
                    $bathroomsArray[$index]['inspectRent'] = $item['inspectRent'] + $val['inspectRent'];
                    $bathroomsArray[$index]['CallRent'] = $item['CallRent'] + $val['CallRent'];
                    $bathroomsArray[$index]['inspectShort'] = $item['inspectShort'] + $val['inspectShort'];
                    $bathroomsArray[$index]['CallShort'] = $item['CallShort'] + $val['CallShort'];
                    unset($bathrooms[$rep]);
                } else {
                    $bathroomsArray[$index]['bathrooms'] = $item['bathrooms'];
                    $bathroomsArray[$index]['inspectBuy'] = $item['inspectBuy'];
                    $bathroomsArray[$index]['CallBuy'] = $item['CallBuy'];
                    $bathroomsArray[$index]['inspectRent'] = $item['inspectRent'];
                    $bathroomsArray[$index]['CallRent'] = $item['CallRent'];
                    $bathroomsArray[$index]['inspectShort'] = $item['inspectShort'];
                    $bathroomsArray[$index]['CallShort'] = $item['CallShort'];
                }
            }
            $index++;
        }


        $toiletsQuery = $em->createQuery("SELECT DISTINCT h.toilets,
                                  (SELECT count(i.id) FROM AppBundle:HouseInspection   i WHERE  i.availableType = :Buy    AND  i.house = h) inspectBuy,
                                  (SELECT count(c.id) FROM AppBundle:CallRequest       c WHERE  c.availableType = :Buy    AND  c.house = h) CallBuy,
                                  (SELECT count(f.id) FROM AppBundle:HouseInspection   f WHERE  f.availableType = :Rent   AND  f.house = h) inspectRent,
                                  (SELECT count(d.id) FROM AppBundle:CallRequest       d WHERE  d.availableType = :Rent   AND  d.house = h) CallRent,
                                  (SELECT count(j.id) FROM AppBundle:HouseInspection   j WHERE  j.availableType = :Short  AND  j.house = h) inspectShort,
                                  (SELECT count(m.id) FROM AppBundle:CallRequest       m WHERE  m.availableType = :Short  AND  m.house = h) CallShort
                                  FROM AppBundle:House h WHERE h.toilets BETWEEN 0 AND 7
                                 ");
        $toiletsQuery
            ->setParameter('Buy', 'Buy')
            ->setParameter('Rent', 'Rent')
            ->setParameter('Short', 'Short');

        $toilets = $toiletsQuery->getResult();
        $toiletsArray = [];
        $index = 0;
        foreach ($toilets as $item) {
            foreach ($toilets as $rep => $val) {
                if ($item['toilets'] == $val['toilets']) {
                    $toiletsArray[$index]['toilets'] = $val['toilets'];
                    $toiletsArray[$index]['inspectBuy'] = $item['inspectBuy'] + $val['inspectBuy'];
                    $toiletsArray[$index]['CallBuy'] = $item['CallBuy'] + $val['CallBuy'];
                    $toiletsArray[$index]['inspectRent'] = $item['inspectRent'] + $val['inspectRent'];
                    $toiletsArray[$index]['CallRent'] = $item['CallRent'] + $val['CallRent'];
                    $toiletsArray[$index]['inspectShort'] = $item['inspectShort'] + $val['inspectShort'];
                    $toiletsArray[$index]['CallShort'] = $item['CallShort'] + $val['CallShort'];
                    unset($toilets[$rep]);
                } else {
                    $toiletsArray[$index]['toilets'] = $item['toilets'];
                    $toiletsArray[$index]['inspectBuy'] = $item['inspectBuy'];
                    $toiletsArray[$index]['CallBuy'] = $item['CallBuy'];
                    $toiletsArray[$index]['inspectRent'] = $item['inspectRent'];
                    $toiletsArray[$index]['CallRent'] = $item['CallRent'];
                    $toiletsArray[$index]['inspectShort'] = $item['inspectShort'];
                    $toiletsArray[$index]['CallShort'] = $item['CallShort'];
                }
            }
            $index++;
        }

        $pricingRange = $this->getDoctrine()->getRepository('AppBundle:ContactCountByPrice')->find(1);

        $pricingQuery = $em->createQuery("SELECT DISTINCT h.id,

                (SELECT count(i.id) FROM AppBundle:HouseInspection i WHERE  i.availableType = :Buy  AND i.house = h.id) inspectBuy,
                (SELECT count(c.id) FROM AppBundle:CallRequest c WHERE  c.availableType = :Buy  AND c.house = h.id) CallBuy,
                (SELECT count(f.id) FROM AppBundle:HouseInspection f WHERE  f.availableType = :Rent  AND f.house = h.id) inspectRent,
                (SELECT count(d.id) FROM AppBundle:CallRequest d WHERE  d.availableType = :Rent  AND d.house = h.id) CallRent,
                (SELECT count(j.id) FROM AppBundle:HouseInspection j WHERE  j.availableType = :Short  AND j.house = h.id) inspectShort,
                (SELECT count(m.id) FROM AppBundle:CallRequest m WHERE  m.availableType = :Short  AND m.house = h.id) CallShort

                 FROM AppBundle:House h 
                 WHERE (h.priceBuy BETWEEN :minSalePrice
                         AND :maxSalePrice
                         OR h.priceRent BETWEEN :minRentPrice
                         AND :maxRentPrice
                         OR h.priceShort BETWEEN :minShortPrice
                         AND :maxShortPrice)
                         
         ");

        $pricingQuery
            ->setParameter('Buy', 'Buy')
            ->setParameter('Rent', 'Rent')
            ->setParameter('Short', 'Short')
            ->setParameter('minSalePrice', $pricingRange->getMinSalePrice())
            ->setParameter('maxSalePrice', $pricingRange->getMaxSalePrice())
            ->setParameter('minRentPrice', $pricingRange->getMinRentPrice())
            ->setParameter('maxRentPrice', $pricingRange->getMaxRentPrice())
            ->setParameter('minShortPrice', $pricingRange->getMinShortPrice())
            ->setParameter('maxShortPrice', $pricingRange->getMaxShortPrice())
        ;

        $pricingContact = $pricingQuery->getResult();
        $buy = 0;
        $rent = 0;
        $short = 0;
        foreach ($pricingContact as $item) {
            $buy =  $buy +  $item['inspectBuy'] + $item['CallBuy'];
            $rent = $rent +  $item['inspectRent'] + $item['CallRent'];
            $short = $short + $item['inspectShort'] + $item['CallShort'];
        }



        $pricingQueryShort = $em->createQuery("SELECT a.name,
        
        (SELECT COUNT(s.id) FROM AppBundle:HouseInspection s WHERE s.availableType = :Short AND s.house = h) inspectShort,
        
        (SELECT COUNT(x.id) FROM AppBundle:CallRequest x WHERE x.availableType = :Short AND x.house = h) cellShort
        
        FROM AppBundle:Area a 
        
        JOIN AppBundle:House h IN h.area = a ORDER BY inspectShort  DESC, cellShort  DESC
                                                
         ");

        $pricingQueryShort
            ->setMaxResults(5)
            ->setParameter('Short', 'Short');

        $pricingShortCount = $pricingQueryShort->getResult();


        $pricingQueryBuy = $em->createQuery("SELECT a.name,
        
        (SELECT COUNT(s.id) FROM AppBundle:HouseInspection s WHERE s.availableType = :Buy AND s.house = h) inspectShort,
        
        (SELECT COUNT(x.id) FROM AppBundle:CallRequest x WHERE x.availableType = :Buy AND x.house = h) cellShort
        
        FROM AppBundle:Area a 
        
        JOIN AppBundle:House h IN h.area = a ORDER BY inspectShort  DESC, cellShort  DESC
                                                
         ");

        $pricingQueryBuy
            ->setMaxResults(5)
            ->setParameter('Buy', 'Buy');

        $pricingBuyCount = $pricingQueryBuy->getResult();

        $pricingQueryRent = $em->createQuery("SELECT a.name,
        
        (SELECT COUNT(s.id) FROM AppBundle:HouseInspection s WHERE s.availableType = :Rent AND s.house = h) inspectShort,
        
        (SELECT COUNT(x.id) FROM AppBundle:CallRequest x WHERE x.availableType = :Rent AND x.house = h) cellShort
        
        FROM AppBundle:Area a 
        
        JOIN AppBundle:House h IN h.area = a ORDER BY inspectShort  DESC, cellShort  DESC
                                                
         ");

        $pricingQueryRent
            ->setMaxResults(5)
            ->setParameter('Rent', 'Rent');

        $pricingRentCount = $pricingQueryRent->getResult();


        $newDate = new \DateTime('- 7 month');
        $byHouseKindArr = [];
        $num = 7;
        $index = 0;
        for ($i=1; $i <= 7; $i++){
            $x = $num-1;
            $byHouseKindQuery = $em->createQuery("

            SELECT count(h.id), h.title, h.bedrooms, h.bathrooms, h.toilets,

            (SELECT COUNT(b.id) FROM AppBundle:HouseInspection b WHERE b.requestedAt BETWEEN :after AND :before AND b.house = h GROUP BY h.id) x,

            (SELECT COUNT(a.id)	FROM AppBundle:CallRequest a WHERE a.requestedAt BETWEEN :after AND :before AND a.house = h GROUP BY h.id) ar

            FROM AppBundle:House h WHERE h.kind = 'bungalow' GROUP BY h.id ORDER BY x DESC, ar DESC
        ");

            $byHouseKindQuery
                ->setParameter('after', new \DateTime("- {$num} month"))
                ->setParameter('before', new \DateTime("- {$x} month"))
            ->setMaxResults(1);

            $byHouseKind = $byHouseKindQuery->getResult();
            $byHouseKindArr['data'][$index] = $byHouseKind;
            $byHouseKindArr['date'][$index] = new \DateTime("- {$x} month");
            $num--;
            $index++;
        }

        return $this->render('dashboard/sales_analyses/sales_advice.html.twig',
            array(
                'sales' => $house,
                'bedrooms' => $bedroomsArray,
                'bathrooms' => $bathroomsArray,
                'toilets' => $toiletsArray,
                'pricingContact' => $pricingContact,
                'short' => $short,
                'rent' => $rent,
                'buy' => $buy,
                'pricingShortCount' => $pricingShortCount,
                'pricingBuyCount' => $pricingBuyCount,
                'pricingRentCount' => $pricingRentCount,
                'arr' => $byHouseKindArr,
            ));
    }


 /*   /**
     * @Route("/room-count-contact-analysis", name="room_count_contact_analysis")
     */
    /*public function roomCountContactAction()
    {

        $em = $this->getDoctrine()->getManager();
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
            ->setParameter('Short', 'Short');

        $house = $query->getResult();
        $arr = [];
        $index = 0;
        foreach ($house as $item) {
            foreach ($house as $rep => $val) {
                if ($item['bedrooms'] == $val['bedrooms']) {
                    $arr[$index]['bedrooms'] = $val['bedrooms'];
                    $arr[$index]['inspectBuy'] = $item['inspectBuy'] + $val['inspectBuy'];
                    $arr[$index]['CallBuy'] = $item['CallBuy'] + $val['CallBuy'];
                    $arr[$index]['inspectRent'] = $item['inspectRent'] + $val['inspectRent'];
                    $arr[$index]['CallRent'] = $item['CallRent'] + $val['CallRent'];
                    $arr[$index]['inspectShort'] = $item['inspectShort'] + $val['inspectShort'];
                    $arr[$index]['CallShort'] = $item['CallShort'] + $val['CallShort'];
                    unset($house[$rep]);
                } else {
                    $arr[$index]['bedrooms'] = $item['bedrooms'];
                    $arr[$index]['inspectBuy'] = $item['inspectBuy'];
                    $arr[$index]['CallBuy'] = $item['CallBuy'];
                    $arr[$index]['inspectRent'] = $item['inspectRent'];
                    $arr[$index]['CallRent'] = $item['CallRent'];
                    $arr[$index]['inspectShort'] = $item['inspectShort'];
                    $arr[$index]['CallShort'] = $item['CallShort'];
                }
            }
            $index++;
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
            ->setParameter('Short', 'Short');

        $house2 = $query2->getResult();
        $arr2 = [];
        $index = 0;
        foreach ($house2 as $item) {
            foreach ($house2 as $rep => $val) {
                if ($item['bathrooms'] == $val['bathrooms']) {
                    $arr2[$index]['bathrooms'] = $val['bathrooms'];
                    $arr2[$index]['inspectBuy'] = $item['inspectBuy'] + $val['inspectBuy'];
                    $arr2[$index]['CallBuy'] = $item['CallBuy'] + $val['CallBuy'];
                    $arr2[$index]['inspectRent'] = $item['inspectRent'] + $val['inspectRent'];
                    $arr2[$index]['CallRent'] = $item['CallRent'] + $val['CallRent'];
                    $arr2[$index]['inspectShort'] = $item['inspectShort'] + $val['inspectShort'];
                    $arr2[$index]['CallShort'] = $item['CallShort'] + $val['CallShort'];
                    unset($house2[$rep]);
                } else {
                    $arr2[$index]['bathrooms'] = $item['bathrooms'];
                    $arr2[$index]['inspectBuy'] = $item['inspectBuy'];
                    $arr2[$index]['CallBuy'] = $item['CallBuy'];
                    $arr2[$index]['inspectRent'] = $item['inspectRent'];
                    $arr2[$index]['CallRent'] = $item['CallRent'];
                    $arr2[$index]['inspectShort'] = $item['inspectShort'];
                    $arr2[$index]['CallShort'] = $item['CallShort'];
                }
            }
            $index++;
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
            ->setParameter('Short', 'Short');

        $house3 = $query3->getResult();
        $arr3 = [];
        $index = 0;
        foreach ($house3 as $item) {
            foreach ($house3 as $rep => $val) {
                if ($item['toilets'] == $val['toilets']) {
                    $arr3[$index]['toilets'] = $val['toilets'];
                    $arr3[$index]['inspectBuy'] = $item['inspectBuy'] + $val['inspectBuy'];
                    $arr3[$index]['CallBuy'] = $item['CallBuy'] + $val['CallBuy'];
                    $arr3[$index]['inspectRent'] = $item['inspectRent'] + $val['inspectRent'];
                    $arr3[$index]['CallRent'] = $item['CallRent'] + $val['CallRent'];
                    $arr3[$index]['inspectShort'] = $item['inspectShort'] + $val['inspectShort'];
                    $arr3[$index]['CallShort'] = $item['CallShort'] + $val['CallShort'];
                    unset($house3[$rep]);
                } else {
                    $arr3[$index]['toilets'] = $item['toilets'];
                    $arr3[$index]['inspectBuy'] = $item['inspectBuy'];
                    $arr3[$index]['CallBuy'] = $item['CallBuy'];
                    $arr3[$index]['inspectRent'] = $item['inspectRent'];
                    $arr3[$index]['CallRent'] = $item['CallRent'];
                    $arr3[$index]['inspectShort'] = $item['inspectShort'];
                    $arr3[$index]['CallShort'] = $item['CallShort'];
                }
            }
            $index++;
        }

        return $this->render('dashboard/sales_analyses/room_count_contact_analysis.html.twig',
            array(
                'analysis' => $arr,
                'bathrooms' => $arr2,
                'toilets' => $arr3,
            ));
    }*/
}