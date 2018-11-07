<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10/1/2018
 * Time: 5:25 PM
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AnnouncementController extends Controller
{
    /**
     * @Route("/houses-announcements/{paginate}", name="announcement_list_page")
     */
    public function announcementAction(Request $request, $paginate = 1)
    {
        $uri = $request->server->get('QUERY_STRING');

        $em = $this->getDoctrine()->getManager();
        $houseRepository = $em->getRepository('AppBundle:House');

        $states = $em->getRepository('AppBundle:State')->findBy([], ['name' => 'ASC']);
        $lgas = $em->getRepository('AppBundle:Lga')->findBy([], ['name' => 'ASC']);
        $areas = $em->getRepository('AppBundle:Area')->findBy([], ['name' => 'ASC']);

        $search = $request->get('search');
        $rent = $request->get('rent');
        $buy = $request->get('buy');
        $short_stay = $request->get('short_stay');
        $bedrooms = $request->get('bedrooms');
        $bathrooms = $request->get('bathrooms');
        $toilets = $request->get('toilets');
        $min = $request->get('min');
        $max = $request->get('max');
        $state = $request->get('state');
        $lga = $request->get('lga');
        $area = $request->get('area');

        $postsPerPage = 18;
        $paginationTotal = 1;

        $offset = $paginate - 1;
        if ($paginate !== 1) {
            $offset = $postsPerPage * $paginate - $postsPerPage;
        }

        if (!empty($search)) {
            $houseQuery = $em->createQuery("
                SELECT h FROM AppBundle:House h
                
                  WHERE h.title LIKE  :search  
                
                  AND (h.isRent = :rent OR :rent = :all OR h.isBuy = :buy OR :buy =:all OR h.isShort = :short_stay OR :short_stay = :all)
                 
                  AND (h.isAvailable = 1
                  
                  AND h.isDeleted = 0
                  
                  AND h.deactivate = 0)
                 
                  ORDER BY h.createdAt DESC   
            ");
            $houseQuery
                ->setParameter('search', $search."%")
                ->setParameter('all', 'all')
                ->setParameter('rent', $rent)
                ->setParameter('buy', $buy)
                ->setParameter('short_stay', $short_stay);

        } else {

            $offset = $paginate - 1;
            if ($paginate !== 1) {
                $offset = $postsPerPage * $paginate - $postsPerPage;
            }

            $query = $em->createQuery("

                SELECT count(h) FROM AppBundle:House h
                            
                  JOIN h.lgaId l
                  
                  WHERE (l.id = :lga or :lga = :all)  
                           
                  AND (h.bedrooms = :bedrooms OR :bedrooms = :all)

                  AND (h.bathrooms = :bathrooms OR :bathrooms = :all)

                  AND (h.toilets = :toilets OR :toilets = :all)
                  
                  AND (h.isRent = :rent OR :rent = :all OR h.isBuy = :buy OR :buy =:all OR h.isShort = :short_stay OR :short_stay = :all)
                  
                  AND ( ( (h.priceRent > :min  or :min = :all)  AND (h.priceRent < :max or :max = :all))
                  
                  OR ( (h.priceBuy > :min  or :min = :all)  AND (h.priceBuy < :max or :max = :all))
                  
                  OR ( (h.priceShort > :min  or :min = :all)  AND (h.priceShort < :max or :max = :all)))
                  
                  AND (h.isAvailable = 1
                  
                  AND h.isDeleted = 0
                  
                  AND h.deactivate = 0)
                  
                  ORDER BY h.createdAt DESC   
            ");
//
            $query
                ->setParameter('all', 'all')
                ->setParameter('lga', $lga)
//                ->setParameter('area', $area)
//                ->setParameter('state', $state)
                ->setParameter('bedrooms', $bedrooms)
                ->setParameter('bathrooms', $bathrooms)
                ->setParameter('toilets', $toilets)
                ->setParameter('rent', $rent)
                ->setParameter('buy', $buy)
                ->setParameter('short_stay', $short_stay)
                ->setParameter('max', intval($max))
                ->setParameter('min', intval($min));

            $blogsTotalCount = $query->getSingleScalarResult();


            $paginationTotal = ceil($blogsTotalCount / $postsPerPage);


            $houseQuery = $em->createQuery("
                SELECT h FROM AppBundle:House h
                  
                  JOIN h.lgaId l
                  
                  WHERE (l.id = :lga or :lga = :all)  
                           
                  AND (h.bedrooms = :bedrooms OR :bedrooms = :all)

                  AND (h.bathrooms = :bathrooms OR :bathrooms = :all)

                  AND (h.toilets = :toilets OR :toilets = :all)
                  
                  AND (h.isRent = :rent OR :rent = :all OR h.isBuy = :buy OR :buy =:all OR h.isShort = :short_stay OR :short_stay = :all)
                  
                  AND ( ( (h.priceRent > :min  or :min = :all)  AND (h.priceRent < :max or :max = :all))
                  
                  OR ( (h.priceBuy > :min  or :min = :all)  AND (h.priceBuy < :max or :max = :all))
                  
                  OR ( (h.priceShort > :min  or :min = :all)  AND (h.priceShort < :max or :max = :all)))
                 
                  AND (h.isAvailable = 1
                  
                  AND h.isDeleted = 0
                  
                  AND h.deactivate = 0)
                 
                  ORDER BY h.createdAt DESC  
            ");
            $houseQuery
                ->setParameter('all', 'all')
                ->setParameter('lga', $lga)
//                ->setParameter('area', $area)
//                ->setParameter('state', $state)
                ->setParameter('bedrooms', $bedrooms)
                ->setParameter('bathrooms', $bathrooms)
                ->setParameter('toilets', $toilets)
                ->setParameter('rent', $rent)
                ->setParameter('buy', $buy)
                ->setParameter('short_stay', $short_stay)
                ->setParameter('max', intval($max))
                ->setParameter('min', intval($min))
                ->setFirstResult($offset)
                ->setMaxResults($postsPerPage);

        }
        $houses = $houseQuery->getResult();

        return $this->render('announcement/list.html.twig',
            array(
                'houses' => $houses,
                'rent' => $rent,
                'buy' => $buy,
                'short_stay' => $short_stay,
                'bedrooms' => $bedrooms,
                'bathrooms' => $bathrooms,
                'toilets' => $toilets,
                'min' => $min,
                'max' => $max,
                'states' => $states,
                'lgas' => $lgas,
                'areas' => $areas,
                'old_state' => $state,
                'old_lga' => $lga,
                'old_area' => $area,
                'paginate' => $paginate,
                'paginationTotal' => $paginationTotal,
                'uri' => $uri,
            ));
    }

    /**
     * @Route("/houses-view/{slug}", name="announcement_view_page")
     */
    public function announcementViewAction(Request $request, $slug)
    {

        $house = $this->getDoctrine()->getRepository("AppBundle:House")->find($slug);
        $user = $house->getAgency();

        return $this->render('announcement/view.html.twig',
            array(
                'house' => $house,
            ));
    }
}