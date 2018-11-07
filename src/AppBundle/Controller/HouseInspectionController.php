<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10/16/2018
 * Time: 4:10 PM
 */

namespace AppBundle\Controller;


use AppBundle\Service\ContactCount;
use AppBundle\Service\SendEmail;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HouseInspectionController extends Controller
{
    /**
     * @Route("house-inspection-requests/{paginate}", name="house_inspection_requests")
     */
    public function houseInspectionRequestAction(Request $request, $paginate = 1)
    {

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
            ->andWhere('r.accept = 0 and r.dismiss = 0')
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
            ->andWhere('r.accept = 0 and r.dismiss = 0')
            ->addOrderBy('r.requestedAt', 'DESC')
            ->addOrderBy('r.answeredAt', 'ASC')
            ->setParameter('agency', $this->getUser())
            ->setFirstResult($offset)
            ->setMaxResults($housePerPage)
            ->getQuery()
        ;

        $propertyInspections = $query->getResult();

        return $this->render('dashboard/property_request/property_inspection.html.twig',
            array(
                'propertyInspections' => $propertyInspections,
                'paginate' => $paginate,
                'paginationTotal' => $paginationTotal
            ));
    }


    /**
     * @Route("accept-house-inspection-requests/{slug}/{house}", name="accept_house_inspection_requests")
     */
    public function acceptHouseInspectionRequestsAction(Request $request, $slug, $house, SendEmail $sendEmail, ContactCount $contactCount)
    {

        $em = $this->getDoctrine()->getManager();

        $agency = $em->getRepository('AppBundle:Agency')->find($this->getUser());

        $agencyPlan = $em->getRepository('AppBundle:UserPlan')->findOneBy(['user' => $agency]);

        $houseInspectionRepository = $em->getRepository('AppBundle:HouseInspection');

        $houseInspection = $houseInspectionRepository->find($slug);

        /* ete user@ uni plan@ u  unlimita */
        if ($agencyPlan->getActive() == 1 && $agencyPlan->getPlan()->getId() != 3) {

            $houseInspection->setAnsweredAt(new \DateTime());
            $houseInspection->setAccept(true);
            $em->flush();
            $contactCount->contactCount($agency);
            $sendEmail->sendEmail($agency,$agency->getEmail());
            return $this->redirectToRoute('house_inspection_requests');
        }


        /* ete user@ uni plan@ u  coinner@ minus petqa arvi */
        if ($agencyPlan->getActive() == 1 && $agencyPlan->getPlan()->getId() == 3) {

            $house = $em->getRepository('AppBundle:House')->find($house);

            $availableType = $houseInspection->getAvailableType();
            $checkAvailableType = 'getIs' . $availableType;
            $availableTypePrice = 'getPrice' . $availableType;

            /* check isset that available type*/
            if ($house->$checkAvailableType() == true) {

                $availableTypePrice = $house->$availableTypePrice();
                $oneYearPrice = null;

                if ($availableType == 'Rent') {

                    $oneYearPrice = $availableTypePrice * 0.59 / 100;

                } elseif ($availableType == 'Short') {

                    $oneYearPrice = $availableTypePrice * 0.59 / 100 / 12;

                } elseif ($availableType == 'Buy') {

                    $oneYearPrice = $availableTypePrice * 0.064 / 100;
                }

                $totalBudget = $agency->getBudget();
                /* check id coins is not equals 0 */

                $oneYearPrice = round($oneYearPrice, 0);
                if ($totalBudget - $oneYearPrice >= 0) {

                    $limitBalance = $agencyPlan->getLimitBalanceAmount();

                    if ($limitBalance != null) {

                        if ($limitBalance - $oneYearPrice >= 0) {

                            $agencyPlan->setLimitBalanceAmount($limitBalance - $oneYearPrice);
                            $houseInspection->setAnsweredAt(new \DateTime());
                            $houseInspection->setAccept(true);
                            $agency->setBudget($totalBudget - $oneYearPrice);
                            $em->flush();
                            $request = 'house_inspection_requests';

                            return $this->forward('AppBundle:CallRequest:blockLimit', array('request' => $request));
//                            $this->addFlash('success', 'You accepted Inspection request successfully');
//                            return $this->redirectToRoute('house_inspection_requests');

                        } else {

                            $this->addFlash('error', 'Please remove Limit to accept this request');
                            return $this->redirectToRoute('house_inspection_requests');
                        }
                    }

                    $houseInspection->setAnsweredAt(new \DateTime());
                    $houseInspection->setAccept(true);
                    $agency->setBudget($totalBudget - $oneYearPrice);
                    $em->flush();
                    $contactCount->contactCount($agency);
                    $sendEmail->sendEmail($agency,$agency->getEmail());
                    return $this->redirectToRoute('house_inspection_requests');

                } else {

                    $this->addFlash('error', 'You coins are less please buy coins');
                    return $this->redirectToRoute('house_inspection_requests');
                }


            }

        }

    }

    /**
     * @Route("dismiss-house-inspection-requests/{slug}", name="dismiss_house_inspection_requests")
     */
    public function dismissHouseInspectionRequestsAction(Request $request, $slug)
    {

        $em = $this->getDoctrine()->getManager();

        $houseInspectionRepository = $em->getRepository('AppBundle:HouseInspection');

        $houseInspection = $houseInspectionRepository->find($slug);

        $houseInspection->setAnsweredAt(new \DateTime());
        $houseInspection->setDismiss(true);
        $em->flush();
        $this->addFlash('error', 'You dismissed Inspection request successfully');
        return $this->redirectToRoute('house_inspection_requests');
    }
}