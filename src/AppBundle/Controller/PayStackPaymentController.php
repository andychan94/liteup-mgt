<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 9/15/2018
 * Time: 11:00 AM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Notification;
use AppBundle\Entity\PaymentOrder;
use Matscode\Paystack\Transaction;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

// for Debugging purpose

class PayStackPaymentController extends Controller
{
    /**
     * @Route("/pay-now", name="pay_now")
     */
    public function payAction(Request $request)
    {
        $amount = $request->get('amount');

        if ($amount < 10000) {
            $this->addFlash('error', 'Price less then 10,000');
            return $this->redirectToRoute('buy_coins_dashboard');
        }

        $secretKey = 'sk_test_f5b94bb19302f92b108325b38e816507982c2267';
        $Transaction = new Transaction($secretKey);
        $response = $Transaction
            ->setCallbackUrl("{$request->getSchemeAndHttpHost()}/callback")// to override/set callback_url, it can also be set on your dashboard
            ->setEmail($this->getUser()->getEmail())
            ->setAmount($amount)// amount is treated in Naira while using this method
            ->initialize();

        return $this->redirect($response->authorizationUrl);
    }

    /**
     * @Route("/callback", name="callback")
     */
    public function callbackAction(Request $request, \Swift_Mailer $mailer)
    {

        $secretKey = 'sk_test_f5b94bb19302f92b108325b38e816507982c2267';
        $Transaction = new Transaction($secretKey);

        $response = $Transaction->verify();

        if ($response->status == true) {
            $data = $response->data;
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('AppBundle:Agency')->find($this->getUser());

            $pay = new PaymentOrder();

            $pay->setUser($this->getUser());
            $pay->setPayId($data->id);
            $pay->setAmount($data->amount / 100);
            $pay->setIpAddress($data->ip_address);
            $pay->setAuthorizationCode($data->authorization->authorization_code);
            $pay->setBin($data->authorization->bin);
            $pay->setLast4($data->authorization->last4);
            $pay->setExpMonth($data->authorization->exp_month);
            $pay->setExpYear($data->authorization->exp_month);
            $pay->setChanel($data->authorization->channel);
            $pay->setCardType($data->authorization->card_type);
            $pay->setBank($data->authorization->bank);
            $pay->setCountryCode($data->authorization->country_code);
            $pay->setBrand($data->authorization->brand);
            $pay->setCustomerId($data->customer->id);
            $pay->setCustomerEmail($data->customer->email);
            $pay->setCustomerCode($data->customer->customer_code);
            $pay->setPaidAt($data->paidAt);
            $pay->setCreatedAt($data->createdAt);
            $pay->setTransactionDate($data->transaction_date);
            $em->persist($pay);
            $total = $user->getBudget() + $data->amount / 100;

            if ($user->getTotalBudgetLimitAt() < new \DateTime('- 7 day') && $user->getTotalBudgetLimitAt() > new \DateTime('- 8 day')) {
                $email = $em->getRepository('AppBundle:Email')->find(14);

                $message = (new \Swift_Message($email->getEmailSubject()))
                    ->setFrom($this->getParameter('mailer_user'))
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->renderView(
                            'Emails/first_coin_purchase.html.twig',
                            array(
                                'name' => $user->getName(),
                                'text' => $email->getEmailText()
                            )
                        ),
                        'text/html'
                    );

                $mailer->send($message);
            }
            $user->setBudget($total);
            $user->setTotalBudget($total);
            $user->setTotalBudgetLimitAt(null);
            $user->setNoCoin(false);
            $em->flush();

            $orderRepository = $em->getRepository('AppBundle:PaymentOrder');
            $notification = new Notification();

            $query = $orderRepository->createQueryBuilder('o')
                ->select('count(o.id)')
                ->where('o.user = :user')
                ->setParameter('user', $user)
                ->getQuery();

            $orderCount = $query->getSingleScalarResult();

            switch ($orderCount) {
                case "1":
                    $email = $em->getRepository('AppBundle:Email')->find(4);

                    $message = (new \Swift_Message($email->getEmailSubject()))
                        ->setFrom($this->getParameter('mailer_user'))
                        ->setTo($user->getEmail())
                        ->setBody(
                            $this->renderView(
                                'Emails/first_coin_purchase.html.twig',
                                array(
                                    'name' => $user->getName(),
                                    'text' => $email->getEmailText()
                                )
                            ),
                            'text/html'
                        );

                    $mailer->send($message);
                    $this->addFlash('success', "{$email->getInSystemAlert()}");
                    break;
                case "5":
                    $email = $em->getRepository('AppBundle:Email')->find(10);

                    $message = (new \Swift_Message($email->getEmailSubject()))
                        ->setFrom($this->getParameter('mailer_user'))
                        ->setTo($user->getEmail())
                        ->setBody(
                            $this->renderView(
                                'Emails/first_coin_purchase.html.twig',
                                array(
                                    'name' => $user->getName(),
                                    'text' => $email->getEmailText()
                                )
                            ),
                            'text/html'
                        );

                    $mailer->send($message);


                    $notification->setAgency($user->getId());
                    $notification->setNotificationText($email->getInSystemAlert());
                    $em->persist($notification);
                    $em->flush();

                    $this->addFlash('success', "{$email->getInSystemAlert()}");
                    break;

                case "10":
                    $email = $em->getRepository('AppBundle:Email')->find(11);

                    $message = (new \Swift_Message($email->getEmailSubject()))
                        ->setFrom($this->getParameter('mailer_user'))
                        ->setTo($user->getEmail())
                        ->setBody(
                            $this->renderView(
                                'Emails/first_coin_purchase.html.twig',
                                array(
                                    'name' => $user->getName(),
                                    'text' => $email->getEmailText()
                                )
                            ),
                            'text/html'
                        );

                    $mailer->send($message);

                    $notification = new Notification();
                    $notification->setAgency($user->getId());
                    $notification->setNotificationText($email->getInSystemAlert());
                    $em->persist($notification);
                    $em->flush();

                    $this->addFlash('success', "{$email->getInSystemAlert()}");
                    break;
                case "20":
                    $email = $em->getRepository('AppBundle:Email')->find(12);

                    $message = (new \Swift_Message($email->getEmailSubject()))
                        ->setFrom($this->getParameter('mailer_user'))
                        ->setTo($user->getEmail())
                        ->setBody(
                            $this->renderView(
                                'Emails/first_coin_purchase.html.twig',
                                array(
                                    'name' => $user->getName(),
                                    'text' => $email->getEmailText()
                                )
                            ),
                            'text/html'
                        );

                    $mailer->send($message);

                    $notification->setAgency($user->getId());
                    $notification->setNotificationText($email->getInSystemAlert());
                    $em->persist($notification);
                    $em->flush();

                    $this->addFlash('success', "{$email->getInSystemAlert()}");
                    break;
                case "50":
                    $email = $em->getRepository('AppBundle:Email')->find(13);

                    $message = (new \Swift_Message($email->getEmailSubject()))
                        ->setFrom($this->getParameter('mailer_user'))
                        ->setTo($user->getEmail())
                        ->setBody(
                            $this->renderView(
                                'Emails/first_coin_purchase.html.twig',
                                array(
                                    'name' => $user->getName(),
                                    'text' => $email->getEmailText()
                                )
                            ),
                            'text/html'
                        );

                    $mailer->send($message);

                    $notification->setAgency($user->getId());
                    $notification->setNotificationText($email->getInSystemAlert());
                    $em->persist($notification);
                    $em->flush();

                    $this->addFlash('success', "{$email->getInSystemAlert()}");
                    break;
                default:
                    $this->addFlash('success', "$response->message");
            }

            return $this->redirectToRoute('dashboard_home');
        };
        $this->addFlash('error', "$response->message");
        return $this->redirectToRoute('dashboard_home');

//           Debuging the $response
//           Debug::print_r( $response);
    }

}