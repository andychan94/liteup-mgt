<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10/16/2018
 * Time: 4:10 PM
 */

namespace AppBundle\Controller;


use AppBundle\Service\BudgetEmail;
use AppBundle\Service\ContactCount;
use AppBundle\Service\SendEmail;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CallRequestController extends Controller
{
    /**
     * @Route("call-requests/{paginate}", name="call_requests")
     */
    public function houseInspectionRequestAction(Request $request, $paginate = 1)
    {

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
            ->andWhere('r.accept = 0 and r.dismiss = 0')
            ->addOrderBy('r.requestedAt', 'DESC')
            ->addOrderBy('r.answeredAt', 'ASC')
            ->setParameter('agency', $this->getUser())
            ->getQuery();
        $callTotalCount = $callTotalCountQuery->getSingleScalarResult();

        $paginationTotal = ceil($callTotalCount / $callPerPage);

        $query = $callRequestRepository->createQueryBuilder('r')
            ->join('r.house', 'h')
            ->where('h.agency = :agency')
            ->andWhere('r.accept = 0 and r.dismiss = 0')
            ->addOrderBy('r.requestedAt', 'DESC')
            ->addOrderBy('r.answeredAt', 'ASC')
            ->setParameter('agency', $this->getUser())
            ->setFirstResult($offset)
            ->setMaxResults($callPerPage)
            ->getQuery();

        $callRequests = $query->getResult();

        return $this->render('dashboard/property_request/call_request.html.twig',
            array(
                'callRequests' => $callRequests,
                'paginate' => $paginate,
                'paginationTotal' => $paginationTotal
            ));
    }

    /**
     * @Route("accept-call-requests/{slug}/{house}", name="accept_call_requests")
     */
    public function acceptHouseInspectionRequestsAction(Request $request, $slug, $house, SendEmail $sendEmail, ContactCount $contactCount)
    {

        $em = $this->getDoctrine()->getManager();

        $agency = $em->getRepository('AppBundle:Agency')->find($this->getUser());

        $agencyPlan = $em->getRepository('AppBundle:UserPlan')->findOneBy(['user' => $agency]);

        $callRequest = $em->getRepository('AppBundle:CallRequest');

        $houseInspection = $callRequest->find($slug);

        /* ete user@ uni plan@ u  unlimita */
        if ($agencyPlan->getActive() == 1 && $agencyPlan->getPlan()->getId() != 3) {

            $houseInspection->setAnsweredAt(new \DateTime());
            $houseInspection->setAccept(true);
            $em->flush();
            $contactCount->contactCount($agency);
            $sendEmail->sendEmail($agency, $agency->getEmail());
            return $this->redirectToRoute('call_requests');
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
                $oneYearPrice = round($oneYearPrice, 0);
                /* check id coins is not equals 0 */
                if ($totalBudget - $oneYearPrice >= 0) {

                    $limitBalance = $agencyPlan->getLimitBalanceAmount();

                    if ($limitBalance != null) {


                        if ($limitBalance - $oneYearPrice > 0) {

                            $agencyPlan->setLimitBalanceAmount($limitBalance - $oneYearPrice);
                            $houseInspection->setAnsweredAt(new \DateTime());
                            $houseInspection->setAccept(true);
                            $agency->setBudget($totalBudget - $oneYearPrice);
                            $em->flush();
                            $request = 'call_requests';
                            $sendEmail->sendEmail($agency, $agency->getEmail());
                            return $this->forward('AppBundle:CallRequest:blockLimit', array('request' => $request));

                        } else {

                            $this->addFlash('error', 'Please remove Limit to accept this request!');
                            return $this->redirectToRoute('call_requests');
                        }
                    }

                    $houseInspection->setAnsweredAt(new \DateTime());
                    $houseInspection->setAccept(true);
                    $agency->setBudget($totalBudget - $oneYearPrice);
                    $em->flush();
                    $contactCount->contactCount($agency);
                    $sendEmail->sendEmail($agency, $agency->getEmail());
                    return $this->redirectToRoute('call_requests');

                } else {
                    $this->addFlash('error', 'You coins are less please buy coins to accept this request');
                    return $this->redirectToRoute('call_requests');
                }

            }

        }

    }

    /**
     * @Route("dismiss-call-requests/{slug}", name="dismiss_call_requests")
     */
    public function dismissHouseInspectionRequestsAction(Request $request, $slug)
    {

        $em = $this->getDoctrine()->getManager();

        $callRequest = $em->getRepository('AppBundle:CallRequest');

        $houseInspection = $callRequest->find($slug);

        $houseInspection->setAnsweredAt(new \DateTime());
        $houseInspection->setDismiss(true);
        $em->flush();
        $this->addFlash('error', 'You dismissed Call request successfully');
        return $this->redirectToRoute('call_requests');
    }

    public function blockLimitAction($request, $agency, SendEmail $sendEmail, ContactCount $contactCount)
    {

        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();

        $houseRepository = $em->getRepository('AppBundle:House');

        $query = $houseRepository->createQueryBuilder('h')
            ->select("min(h.priceRent) Rent, min(h.priceBuy) Buy, min(h.priceShort) Short")
            ->where('h.agency = :agency')
            ->setParameter('agency', $user)
            ->getQuery();

        $houses = $query->getSingleResult();

        $oneYearPrice = null;

        $rentYearPrice = $houses['Rent'] * 0.59 / 100;
        $shortYearPrice = $houses['Short'] * 0.59 / 100 / 12;
        $buyYearPrice = $houses['Buy'] * 0.064 / 100;

        $totalMin = min(round($rentYearPrice, 0), round($shortYearPrice, 0), round($buyYearPrice, 0));

        if ($user->getUserPlan()->getLimitBalanceAmount() < $totalMin) {

            $query = $em->createQueryBuilder()
                ->update('AppBundle:House', 'h')
                ->set('h.deactivate', ':true')
                ->where('h.agency = :agency')
                ->setParameter('agency', $user)
                ->setParameter('true', true)
                ->getQuery();

            $query->execute();

            $this->addFlash('error', 'Your limit end. Please remove limit to activate your property');
            return $this->redirectToRoute($request);

        }
        $contactCount->contactCount($agency);
        $sendEmail->sendEmail($agency, $agency->getEmail());
        return $this->redirectToRoute($request);

    }

}