<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/5/2018
 * Time: 1:52 PM
 */

namespace AppBundle\Controller\Dashboard;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ContactHistoryController extends Controller
{
//
    /**
     * @Route("/call/contact/history/{paginate}", name="call_contact_history")
     */
    public function callHistoryAction($paginate = 1){

        $callRequestRepository = $this->getDoctrine()->getRepository('AppBundle:CallRequest');

        $callPerPage = 15;
        $paginationTotal = 1;


        $offset = $paginate - 1;
        if ($paginate !== 1) {
            $offset = $callPerPage * $paginate - $callPerPage;
        }


        $callTotalCountQuery = $callRequestRepository->createQueryBuilder('r')
            ->select('count(r)')
            ->join('r.house', 'h')
            ->where('h.agency = :agency')
            ->andWhere('r.accept = 1 or r.dismiss = 1')
            ->addOrderBy('r.requestedAt', 'DESC')
            ->addOrderBy('r.answeredAt', 'ASC')
            ->setParameter('agency', $this->getUser())
            ->getQuery();
        $callTotalCount = $callTotalCountQuery->getSingleScalarResult();

        $paginationTotal = ceil($callTotalCount / $callPerPage);

        $query = $callRequestRepository->createQueryBuilder('r')
            ->join('r.house', 'h')
            ->where('h.agency = :agency')
            ->andWhere('r.accept = 1 or r.dismiss = 1')
            ->addOrderBy('r.requestedAt', 'DESC')
            ->addOrderBy('r.answeredAt', 'ASC')
            ->setParameter('agency', $this->getUser())
            ->setFirstResult($offset)
            ->setMaxResults($callPerPage)
            ->getQuery();

        $callRequests = $query->getResult();

        return $this->render('dashboard/contact_history/call_request_history.html.twig',
            array(
                'callRequests' => $callRequests,
                'paginate' => $paginate,
                'paginationTotal' => $paginationTotal
            ));
    }

    /**
     * @Route("/inspection/contact/history/{paginate}", name="inspection_contact_history")
     */
    public function inspectionHistoryAction($paginate = 1){

        $houseInspectionRepository = $this->getDoctrine()->getRepository('AppBundle:HouseInspection');

        $housePerPage = 15;
        $paginationTotal = 1;

        $blogRepository = $this->getDoctrine()->getRepository('AppBundle:Blog');

        $offset = $paginate - 1;
        if($paginate !== 1){ $offset = $housePerPage * $paginate - $housePerPage; }


        $houseTotalCountQuery = $houseInspectionRepository->createQueryBuilder('r')
            ->select('count(r.id)')
            ->join('r.house', 'h')
            ->where('h.agency = :agency')
            ->andWhere('r.accept = 1 or r.dismiss = 1')
            ->addOrderBy('r.requestedAt', 'DESC')
            ->addOrderBy('r.answeredAt', 'ASC')
            ->setParameter('agency', $this->getUser())
            ->getQuery();
        ;
        $houseTotalCount = $houseTotalCountQuery->getSingleScalarResult();

        $paginationTotal = ceil($houseTotalCount  / $housePerPage);

        $query = $houseInspectionRepository->createQueryBuilder('r')
            ->join('r.house', 'h')
            ->where('h.agency = :agency')
            ->andWhere('r.accept = 1 or r.dismiss = 1')
            ->addOrderBy('r.requestedAt', 'DESC')
            ->addOrderBy('r.answeredAt', 'ASC')
            ->setParameter('agency', $this->getUser())
            ->setFirstResult($offset)
            ->setMaxResults($housePerPage)
            ->getQuery()
        ;

        $propertyInspections = $query->getResult();

        return $this->render('dashboard/contact_history/inspection_request_history.html.twig',
            array(
                'propertyInspections' => $propertyInspections,
                'paginate' => $paginate,
                'paginationTotal' => $paginationTotal
            ));
    }

    /**
     * @Route("/user/property/request/history/{paginate}", name="user_property_request_history")
     */
    public function userPropertyRequestHistoryAction($paginate = 1)
    {

        $userHouseRepository = $this->getDoctrine()->getRepository('AppBundle:UserHouseRequest');

        $housePerPage = 15;
        $paginationTotal = 1;


        $offset = $paginate - 1;
        if ($paginate !== 1) {
            $offset = $housePerPage * $paginate - $housePerPage;
        }

        $houseTotalCountQuery = $userHouseRepository->createQueryBuilder('q')
            ->select('count(q.id)')
            ->where('q.agency = :agency')
            ->orderBy('q.createdAt', 'DESC')
            ->setParameter('agency', $this->getUser())
            ->getQuery();
        $houseTotalCount = $houseTotalCountQuery->getSingleScalarResult();

        $paginationTotal = ceil($houseTotalCount / $housePerPage);

        $query = $userHouseRepository->createQueryBuilder('q')
            ->select('h,u,q')
            ->join('q.house', 'h')
            ->join('h.user', 'u')
            ->where('q.agency = :agency')
            ->orderBy('q.createdAt', 'DESC')
            ->setParameter('agency', $this->getUser())
            ->setFirstResult($offset)
            ->setMaxResults($housePerPage)
            ->getQuery();
        $houses = $query->getResult();

        return $this->render('dashboard/contact_history/user_hose_request.html.twig',
            array(
                'houses' => $houses,
                'paginate' => $paginate,
                'paginationTotal' => $paginationTotal
            ));
    }
}