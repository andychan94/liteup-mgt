<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10/26/2018
 * Time: 3:51 PM
 */

namespace AppBundle\Service;


use AppBundle\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ContactCount
{
    protected $em;
    protected $mailer;
    protected $container;

    public function __construct(EntityManagerInterface $entityManager, \Swift_Mailer $mailer, ContainerInterface $container)
    {
        $this->em = $entityManager;
        $this->mailer = $mailer;
        $this->container = $container;
    }

    public function contactCount($agency)
    {
        $em = $this->em;

        $contactCount = $em->getRepository('AppBundle:ContactCount')->findOneBy(['agency' => $agency->getId()]);

        if ($contactCount == true) {
            $contactCount->setCount($contactCount->getCount() + 1);
            $contactCount->setUpdateAt(new \DateTime());
        } else {
            $newContactCount = new \AppBundle\Entity\ContactCount();
            $newContactCount->setCount(1);
            $newContactCount->setAgency($agency->getId());
            $newContactCount->setUpdateAt(new \DateTime());
            $em->persist($newContactCount);
        }
        $em->flush();

        $houseRepository = $em->getRepository('AppBundle:House');
        $sender_email = $this->container->getParameter('mailer_user');
        $query = $houseRepository->createQueryBuilder('h')
            ->select("min(h.priceRent) Rent, min(h.priceBuy) Buy, min(h.priceShort) Short")
            ->where('h.agency = :agency')
            ->setParameter('agency', $agency)
            ->getQuery();

        $houses = $query->getSingleResult();

        $rentYearPrice = $houses['Rent'] * 0.59 / 100;
        $shortYearPrice = $houses['Short'] * 0.59 / 100 / 12;
        $buyYearPrice = $houses['Buy'] * 0.064 / 100;

        $totalMin = min(round($rentYearPrice, 0), round($shortYearPrice, 0), round($buyYearPrice, 0));

        $totalBudget = $agency->getTotalBudget();

        $balanceBidget = $agency->getBudget();

        $percent = round((100 - $balanceBidget / $totalBudget * 100));
        $notification = new Notification();

        if ($percent >= 70 && $percent < 70){
            $email = $em->getRepository('AppBundle:EmailReaction')->find(12);
            $body = $this->container->get('templating')->render('Emails/activation_email.html.twig', array(
                'user' => $agency->getName(),
                'text' => $email->getEmailText(),
            ));
            $message = (new \Swift_Message($email->getEmailSubject()))
                ->setFrom($sender_email)
                ->setTo($agency->getEmail())
                ->setBody($body, 'text/html');

            return $this->mailer->send($message);
        }elseif ($percent >= 90 && $percent < 90){
            $email = $em->getRepository('AppBundle:EmailReaction')->find(13);

            $body = $this->container->get('templating')->render('Emails/activation_email.html.twig', array(
                'user' => $agency->getName(),
                'text' => $email->getEmailText(),
            ));
            $notification->setAgency($agency->getId());
            $notification->setNotificationText($email->getInSystemAlert());
            $em->persist($notification);
            $em->flush();
            $message = (new \Swift_Message($email->getEmailSubject()))
                ->setFrom($sender_email)
                ->setTo($agency->getEmail())
                ->setBody($body, 'text/html');
            return $this->mailer->send($message);
        }elseif ($agency->getBudget() < $totalMin){
            $email = $em->getRepository('AppBundle:EmailReaction')->find(11);

            $body = $this->container->get('templating')->render('Emails/activation_email.html.twig', array(
                'user' => $agency->getName(),
                'text' => $email->getEmailText(),
            ));
            $agency->setTotalBudgetLimitAt(new \DateTime());
            $agency->setNoCoin(true);
            $em->flush();
            $notification->setAgency($agency->getId());
            $notification->setNotificationText($email->getInSystemAlert());
            $em->persist($notification);
            $em->flush();
            $message = (new \Swift_Message($email->getEmailSubject()))
                ->setFrom($sender_email)
                ->setTo($agency->getEmail())
                ->setBody($body, 'text/html');
            return $this->mailer->send($message);
        }else{
            return true;
        }
////        die;
//        switch ($percent) {
//            case  $percent > 70 && $percent < 70:
//                $email = $em->getRepository('AppBundle:EmailReaction')->find(12);
//                $body = $this->container->get('templating')->render('Emails/activation_email.html.twig', array(
//                    'user' => $agency->getName(),
//                    'text' => $email->getEmailText(),
//                ));
//                $message = (new \Swift_Message($email->getEmailSubject()))
//                    ->setFrom($sender_email)
//                    ->setTo($agency->getEmail())
//                    ->setBody($body, 'text/html');
//
//                return $this->mailer->send($message);
//                break;
//            case $percent >= 90 && $percent < 90:
//                $email = $em->getRepository('AppBundle:EmailReaction')->find(13);
//
//                $body = $this->container->get('templating')->render('Emails/activation_email.html.twig', array(
//                    'user' => $agency->getName(),
//                    'text' => $email->getEmailText(),
//                ));
//                $notification->setAgency($agency->getId());
//                $notification->setNotificationText($email->getInSystemAlert());
//                $em->persist($notification);
//                $em->flush();
//                $message = (new \Swift_Message($email->getEmailSubject()))
//                    ->setFrom($sender_email)
//                    ->setTo($agency->getEmail())
//                    ->setBody($body, 'text/html');
//                return $this->mailer->send($message);
//                break;
//            case $agency->getBudget() < $totalMin:
//                $email = $em->getRepository('AppBundle:EmailReaction')->find(11);
//
//                $body = $this->container->get('templating')->render('Emails/activation_email.html.twig', array(
//                    'user' => $agency->getName(),
//                    'text' => $email->getEmailText(),
//                ));
//                $agency->setTotalBudgetLimitAt(new \DateTime());
//                $agency->setNoCoin(true);
//                $em->flush();
//                $notification->setAgency($agency->getId());
//                $notification->setNotificationText($email->getInSystemAlert());
//                $em->persist($notification);
//                $em->flush();
//                $message = (new \Swift_Message($email->getEmailSubject()))
//                    ->setFrom($sender_email)
//                    ->setTo($agency->getEmail())
//                    ->setBody($body, 'text/html');
//                return $this->mailer->send($message);
//                break;
//            default:
//                return true;
//                break;
//
//        }

    }

}