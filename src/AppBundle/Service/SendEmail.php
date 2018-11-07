<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10/25/2018
 * Time: 3:15 PM
 */

namespace AppBundle\Service;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class SendEmail
{
    private $mailer;
    private $container;
    private $em;
    private $flashBag;

    public function __construct(\Swift_Mailer $mailer, ContainerInterface $container, EntityManagerInterface $entityManager, FlashBagInterface $flashBag)
    {
        $this->mailer = $mailer;
        $this->container = $container;
        $this->em = $entityManager;
        $this->flashBag = $flashBag;
    }

    /*---------- send email if has contact ---------*/
    public function sendEmail($agency, $userEmail)
    {
        $em = $this->em;

        $sender_email = $this->container->getParameter('mailer_user');
        $contactCount = $em->getRepository('AppBundle:ContactCount')->findOneBy(['agency' => $agency->getId()]);
        $firstEmail = $em->getRepository('AppBundle:Email')->find(8);
        $email = $em->getRepository('AppBundle:Email')->find(7);


        if ($contactCount->getCount() == 1) {

            $body = $this->container->get('templating')->render('Emails/activation_email.html.twig', array(
                'user' => $agency->getName(),
                'text' => $firstEmail->getEmailText(),
            ));

            $message = (new \Swift_Message($firstEmail->getEmailSubject()))
                ->setFrom($sender_email)
                ->setTo($userEmail)
                ->setBody($body, 'text/html');
            $this->flashBag->add('success', "{$firstEmail->getInSystemAlert()}");

            return $this->mailer->send($message);
        } else {


            $body = $this->container->get('templating')->render('Emails/activation_email.html.twig', array(
                'user' => $agency->getName(),
                'text' => $email->getEmailText(),
            ));

            $message = (new \Swift_Message($email->getEmailSubject()))
                ->setFrom($sender_email)
                ->setTo($userEmail)
                ->setBody($body, 'text/html');
            $this->flashBag->add('success', "{$email->getInSystemAlert()}");
            return $this->mailer->send($message);
        }
    }
}