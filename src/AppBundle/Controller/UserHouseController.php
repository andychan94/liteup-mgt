<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10/9/2018
 * Time: 1:52 PM
 */

namespace AppBundle\Controller;

use AppBundle\Entity\UserHouseRequest;
use AppBundle\Service\ContactCount;
use AppBundle\Service\SendEmail;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserHouseController extends Controller
{
    /**
     * @Route("/house-request/requests/{paginate}", name="property_request_requests")
     */
    public function propertyRequestRequestsAction($paginate = 1)
    {

        $housePerPage = 15;
        $paginationTotal = 1;

        $userHouseRepository = $this->getDoctrine()->getRepository('AppBundle:UserHouse');

        $offset = $paginate - 1;
        if ($paginate !== 1) {
            $offset = $housePerPage * $paginate - $housePerPage;
        }

        $houseTotalCountQuery = $userHouseRepository->createQueryBuilder('h')
            ->select('count(h.id)')
            ->join('h.user', 'u')
            ->orderBy('h.createdAt', 'DESC')
            ->getQuery();
        $houseTotalCount = $houseTotalCountQuery->getSingleScalarResult();

        $paginationTotal = ceil($houseTotalCount / $housePerPage);

        $query = $userHouseRepository->createQueryBuilder('h')
            ->select('h,u')
            ->join('h.user', 'u')
            ->orderBy('h.createdAt', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($housePerPage)
            ->getQuery();
        $houses = $query->getResult();


        return $this->render('dashboard/property_request/user_house.html.twig', array(
            'houses' => $houses,
            'paginate' => $paginate,
            'paginationTotal' => $paginationTotal
        ));
    }




    /**
     * @Route("/accept-request-user-house/{house}", name="accept_request_user_house")
     */
    public function acceptRequestUserHouseAction($house, Request $request, SendEmail $sendEmail, ContactCount $contactCount)
    {
        $em = $this->getDoctrine()->getManager();

        $agency = $em->getRepository("AppBundle:Agency")->find($this->getUser());

        $userHousesRepository = $em->getRepository('AppBundle:UserHouse');

        $agencyPlan = $em->getRepository('AppBundle:UserPlan')->findOneBy(['user' => $agency]);

        $query = $userHousesRepository->createQueryBuilder('h')
            ->select('h')
            ->where('h.id = :id')
            ->setParameter('id', $house)
            ->getQuery();
        $userHouse = $query->getSingleResult();

        if ($agencyPlan == false){
            $this->addFlash('error', 'Please subscribe to plan !'); // TODO error message ete plan chi @ntyrel
            return $this->redirectToRoute('property_request_requests');
        }

        /* ete user@ uni plan@ u  unlimita */
        if ($agencyPlan->getActive() == 1 && $agencyPlan->getPlan()->getId() != 3) {

            $userHouseRequest = new UserHouseRequest();

            $userHouseRequest->setAgency($agency);
            $userHouseRequest->setHouse($userHouse);
            $userHouseRequest->setCreatedAt(new \DateTime());
            $em->persist($userHouseRequest);
            $em->flush();
            $contactCount->contactCount($agency);
            $sendEmail->sendEmail($agency,$agency->getEmail());
//            $this->addFlash('success', 'You accepted User House Request successfully');  // TODO sussecc message ete House requesta arel
            return $this->redirectToRoute('property_request_requests');
        }

        /* ete user@ uni plan@ u  coinner@ minus petqa arvi */
        if ($agencyPlan->getActive() == 1 && $agencyPlan->getPlan()->getId() == 3) {

            $availableTypePrice = $userHouse->getHousePrice();

            $availableType = $userHouse->getAvailableType();

            /* check isset that available type*/
            $oneYearPrice = null;

            if ($availableType == 'Rent') {

                $oneYearPrice = $availableTypePrice * 0.59 / 100;

            } elseif ($availableType == 'Short') {

                $oneYearPrice = $availableTypePrice * 0.59 / 100 / 12;

            } elseif ($availableType == 'Buy') {

                $oneYearPrice = $availableTypePrice * 0.064 / 100;
            }
            $totalBudget = $agency->getBudget();
            $oneYearPrice = round($oneYearPrice, 0);
            /* check id coins is not equals 0 */
            if ($totalBudget - $oneYearPrice >= 0) {
                $userHouseRequest = new UserHouseRequest();
                $limitBalance = $agencyPlan->getLimitBalanceAmount();

                if ($limitBalance != null) {

                    if ($limitBalance - $oneYearPrice >= 0) {

                        $agencyPlan->setLimitBalanceAmount($limitBalance - $oneYearPrice);
                        $userHouseRequest->setAgency($agency);
                        $userHouseRequest->setHouse($userHouse);
                        $userHouseRequest->setCreatedAt(new \DateTime());
                        $em->persist($userHouseRequest);
                        $em->flush();
                        $agency->setBudget($totalBudget - $oneYearPrice);
                        $em->flush();
                        $request = 'property_request_requests';
                        return $this->forward('AppBundle:CallRequest:blockLimit',array('request' => $request, 'agency' => $agency));
//                        $this->addFlash('success', 'You accepted  User House request successfully');
//                        return $this->redirectToRoute('property_request_requests');

                    } else {

                        $this->addFlash('error', 'Please remove Limit to accept this request'); // TODO error message ete limiti patcharov chi karum tequest verci
                        return $this->redirectToRoute('property_request_requests');
                    }
                }

                $userHouseRequest->setAgency($agency);
                $userHouseRequest->setHouse($userHouse);
                $userHouseRequest->setCreatedAt(new \DateTime());
                $em->persist($userHouseRequest);
                $agency->setBudget($totalBudget - $oneYearPrice);
                $em->flush();
                $contactCount->contactCount($agency);
                $sendEmail->sendEmail($agency,$agency->getEmail());
                return $this->redirectToRoute('property_request_requests');

            } else {

                $this->addFlash('error', 'You coins are less please buy coins');// TODO error message ete request @nduneluc coinner@ qicha
                return $this->redirectToRoute('property_request_requests');
            }
        }

        return $this->redirectToRoute('property_request_requests');
    }


}