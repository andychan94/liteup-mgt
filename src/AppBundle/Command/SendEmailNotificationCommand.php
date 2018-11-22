<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10/23/2018
 * Time: 1:56 PM
 */

namespace AppBundle\Command;

use AppBundle\Entity\EmailInterval;
use AppBundle\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendEmailNotificationCommand extends ContainerAwareCommand
{
    protected $em;
    protected static $defaultName = 'app:send-emails';
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
        parent::__construct();

    }


    protected function configure()
    {
//        $this
//            ->setName('app:send-emails')
//            ->setDescription('Send Mail Not active users');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Twig\Error\Error
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        $mailer_user = $container->getParameter('mailer_user');

        $mailer = $container->get('mailer');
        $spool = $mailer->getTransport()->getSpool();
//        $transport = $container->get('swiftmailer.transport.real');
//        $spool->flushQueue($transport);
        $em = $this->em;
        /*--------------- Connect to agency repository ---------------*/
        $agencyRepository = $em->getRepository('AppBundle:Agency');
        ############################################################################
        ##                                                                        ##
        ##         SECTION Not activated 2 days after sign up start               ##
        ##                                                                        ##
        ############################################################################
        /*--------------- //TODO Not activated 2 days after sign up start */

        $email = $em->getRepository('AppBundle:EmailReaction')->find(1);

        /*--------------- Create query to find users that not activated  ---------------*/
        $query = $agencyRepository->createQueryBuilder('a')
            ->where('a.enabled = :false')
            ->andWhere('a.blockMessage = :false')
            ->andWhere('a.createdAt < :date AND a.createdAt > :date2')
            ->setParameter('false', false)
            ->setParameter('date', new \DateTime('- 2 day'))
            ->setParameter('date2', new \DateTime('- 3 day'))
            ->getQuery();

        $agencies = $query->getResult();

        foreach ($agencies as $agency) {
            $body = $container->get('templating')->render('Emails/activation_email.html.twig', array(
                'user' => $agency->getName(),
                'text' => $email->getEmailText(),
            ));

            $message = \Swift_Message::newInstance()
                ->setSubject($email->getEmailSubject())
                ->setFrom($mailer_user)
                ->setTo($agency->getEmail())
                ->setCharset('UTF-8')
                ->setContentType('text/html')
                ->setBody($body);

            $mailer->send($message);
        }
        /*--------------- //TODO Not activated 2 days after sign up end */
        ############################################################################
        ##                                                                        ##
        ##       SECTION Not logo Upload  1 day after first login start           ##
        ##                                                                        ##
        ############################################################################
        /*--------------- //TODO Not logo Upload  1 day after first login start */
        $email = $em->getRepository('AppBundle:EmailReaction')->find(2);

        $notLogoAgencies = $agencyRepository->createQueryBuilder('a')
            ->where('a.logo = :empty')
            ->andWhere('a.firstLogin < :date AND a.firstLogin > :date2')
            ->andWhere('a.blockMessage = :false')
            ->setParameter('empty', "")
            ->setParameter('false', false)
            ->setParameter('date', new \DateTime('- 1 day'))
            ->setParameter('date2', new \DateTime('- 2 day'))
            ->getQuery();

        $agencies = $notLogoAgencies->getResult();

        foreach ($agencies as $agency) {

            $body = $container->get('templating')->render('Emails/activation_email.html.twig', array(
                'user' => $agency->getName(),
                'text' => $email->getEmailText(),
            ));

            $message = \Swift_Message::newInstance()
                ->setSubject($email->getEmailSubject())
                ->setFrom($mailer_user)
                ->setTo($agency->getEmail())
                ->setCharset('UTF-8')
                ->setContentType('text/html')
                ->setBody($body);

            $mailer->send($message);

            $notification = new Notification();
            $notification->setAgency($agency->getId());
            $notification->setNotificationText($email->getInSystemAlert());
            $em->persist($notification);
            $em->flush();

        }
        /*--------------- //TODO Not logo Upload  1 day after first login end */
        ############################################################################
        ##                                                                        ##
        ##   SECTION no property upload  2 days after first login start           ##
        ##                                                                        ##
        ############################################################################
        /*--------------- //TODO no property upload  2 days after first login start*/
        $email = $em->getRepository('AppBundle:EmailReaction')->find(3);

        $noPropertyUploadQuery = $agencyRepository->createQueryBuilder('a')
            ->select('count(h.id), a')
            ->join('a.houses', 'h')
            ->where('a.firstLogin < :date AND a.firstLogin > :date2')
            ->andWhere('a.blockMessage = :false')
            ->groupBy('a')
            ->setParameter('false',false)
            ->setParameter('date', new \DateTime('- 2 day'))
            ->setParameter('date2', new \DateTime('- 3 day'))
            ->getQuery();

        $noPropertyUpload = $noPropertyUploadQuery->getResult();
        foreach ($noPropertyUpload as $agency) {

            if ($agency[0] == null) {

                $body = $container->get('templating')->render('Emails/activation_email.html.twig', array(
                    'user' => $agency[0]->getName(),
                    'text' => $email->getEmailText(),
                ));

                $message = \Swift_Message::newInstance()
                    ->setSubject($email->getEmailSubject())
                    ->setFrom($mailer_user)
                    ->setTo($agency[0]->getEmail())
                    ->setCharset('UTF-8')
                    ->setContentType('text/html')
                    ->setBody($body);

                $mailer->send($message);

                $notification = new Notification();
                $notification->setAgency($agency[0]->getId());
                $notification->setNotificationText($email->getInSystemAlert());
                $em->persist($notification);
                $em->flush();

            }
        }
        /*--------------- //TODO no property upload  2 days after first login end*/
        ############################################################################
        ##                                                                        ##
        ##        SECTION no activity 14 days after first  activation start       ##
        ##                                                                        ##
        ############################################################################
        /*--------------- //TODO nno activity 14 days after first  activation start*/
        $notVerifiedQuery = $agencyRepository->createQueryBuilder('a')
            ->leftJoin('a.verifyRequest', 'v')
            ->leftJoin('a.houses', 'h')
            ->where('v.id is null and h.id is null and a.logo = :empty')
            ->andWhere('a.firstLogin <  :date and a.firstLogin > :date2')
            ->andWhere('a.blockMessage = :false')
            ->setParameter('empty', '')
            ->setParameter('false', false)
            ->setParameter('date', new \DateTime('- 14 day'))
            ->setParameter('date2', new \DateTime('- 15 day'))
            ->getQuery();

        $notVerifiedAgencies = $notVerifiedQuery->getResult();

        $email = $em->getRepository('AppBundle:EmailReaction')->find(4);

        foreach ($notVerifiedAgencies as $agency) {

            $body = $container->get('templating')->render('Emails/activation_email.html.twig', array(
                'user' => $agency->getName(),
                'text' => $email->getEmailText(),
            ));
            $message = \Swift_Message::newInstance()
                ->setSubject($email->getEmailSubject())
                ->setFrom($mailer_user)
                ->setTo($agency->getEmail())
                ->setCharset('UTF-8')
                ->setContentType('text/html')
                ->setBody($body);

            $mailer->send($message);

        }
        /*--------------- //TODO nno activity 14 days after first  activation end*/
        ############################################################################
        ##                                                                        ##
        ##        SECTION No coin purchase 6 days after first login               ##
        ##                                                                        ##
        ############################################################################
        /*--------------- //TODO No coin purchase 6 days after first login start*/
        $noCoinPurchaseQuery = $agencyRepository->createQueryBuilder('a')
            ->select('a')
            ->leftJoin('a.paymentOrder', 'p')
            ->where('a.firstLogin < :date and a.firstLogin > :date2')
            ->andWhere('p.id is null')
            ->andWhere('a.blockMessage = :false')
            ->setParameter('false', false)
            ->setParameter('date', new \DateTime('- 6 day'))
            ->setParameter('date2', new \DateTime('- 7 day'))
            ->getQuery();

        $noCoinPurchase = $noCoinPurchaseQuery->getResult();

        $email = $em->getRepository('AppBundle:EmailReaction')->find(5);

        foreach ($noCoinPurchase as $agency) {

            $body = $container->get('templating')->render('Emails/activation_email.html.twig', array(
                'user' => $agency->getName(),
                'text' => $email->getEmailText(),
            ));
            $message = \Swift_Message::newInstance()
                ->setSubject($email->getEmailSubject())
                ->setFrom($mailer_user)
                ->setTo($agency->getEmail())
                ->setCharset('UTF-8')
                ->setContentType('text/html')
                ->setBody($body);

            $mailer->send($message);

            $notification = new Notification();
            $notification->setAgency($agency->getId());
            $notification->setNotificationText($email->getInSystemAlert());
            $em->persist($notification);
            $em->flush();
        }
        /*--------------- //TODO No coin purchase 6 days after first login end*/
        ############################################################################
        ##                                                                        ##
        ##                      SECTION All End of every month                    ##
        ##                                                                        ##
        ############################################################################
        /*--------------- //TODO All End of every month  Start*/
        $today = new \DateTime('today');
        $lastDay = new \DateTime('last day of this month');
        if ($today->format('Y-m-d') == $lastDay->format('Y-m-d')) {

            $allAgencies = $agencyRepository->findBy(['blockMessage' => false]);

            $email = $em->getRepository('AppBundle:EmailReaction')->find(6);

            foreach ($allAgencies as $agency) {

                $body = $container->get('templating')->render('Emails/activation_email.html.twig', array(
                    'user' => $agency->getName(),
                    'text' => $email->getEmailText(),
                ));
                $message = \Swift_Message::newInstance()
                    ->setSubject($email->getEmailSubject())
                    ->setFrom($mailer_user)
                    ->setTo($agency->getEmail())
                    ->setCharset('UTF-8')
                    ->setContentType('text/html')
                    ->setBody($body);

                $mailer->send($message);

                $notification = new Notification();
                $notification->setAgency($agency->getId());
                $notification->setNotificationText($email->getInSystemAlert());
                $em->persist($notification);
                $em->flush();
            }
        }
        /*--------------- //TODO All End of every month  end*/
        ############################################################################
        ##                                                                        ##
        ##           SECTION Not verified 2 days after property Upload            ##
        ##                                                                        ##
        ############################################################################
        /*--------------- //TODO Not verified 2 days after property Upload  Start*/
        $email = $em->getRepository('AppBundle:EmailReaction')->find(7);

        $notVerifiedQuery = $agencyRepository->createQueryBuilder('a')
            ->select('a')
            ->leftJoin('a.houses', 'p')
            ->leftJoin('a.verifyRequest', 'v')
            ->where('v.id is null')
            ->andWhere('p.createdAt < :date and p.createdAt > :date2')
            ->andWhere('a.blockMessage = :false')
            ->groupBy('p')
            ->orderBy('p.createdAt', 'ASC')
            ->setParameter('false', false)
            ->setParameter('date', new \DateTime('- 2 day'))
            ->setParameter('date2', new \DateTime('- 3 day'))
            ->getQuery();

        $notVerified = $notVerifiedQuery->getResult();


        foreach ($notVerified as $agency) {

            $body = $container->get('templating')->render('Emails/activation_email.html.twig', array(
                'user' => $agency->getName(),
                'text' => $email->getEmailText(),
            ));
            $message = \Swift_Message::newInstance()
                ->setSubject($email->getEmailSubject())
                ->setFrom($mailer_user)
                ->setTo($agency->getEmail())
                ->setCharset('UTF-8')
                ->setContentType('text/html')
                ->setBody($body);

            $mailer->send($message);

            $notification = new Notification();
            $notification->setAgency($agency->getId());
            $notification->setNotificationText($email->getInSystemAlert());
            $em->persist($notification);
            $em->flush();

        }
        /*--------------- //TODO Not verified 2 days after property Upload  end*/
        ############################################################################
        ##                                                                        ##
        ##           SECTION No contact  21 days after property Upload            ##
        ##                                                                        ##
        ############################################################################
        /*--------------- //TODO No contact  21 days after property Upload  Start*/
        $email = $em->getRepository('AppBundle:EmailReaction')->find(8);

        $noContactQuery = $agencyRepository->createQueryBuilder('a')
            ->select('a')
            ->leftJoin('AppBundle\Entity\ContactCount', 'c',  'WITH','c.agency = a.id')
            ->leftJoin('a.houses','p')
            ->where('p.createdAt < :date')
            ->andWhere('a.blockMessage = :false')
            ->andWhere('c.count is null')
            ->groupBy('p')
            ->orderBy('p.createdAt','ASC')
            ->setParameter('false', false)
            ->setParameter('date', new \DateTime('- 21 day'))
            ->getQuery()
        ;
        $noContact = $noContactQuery->getResult();

        foreach ($noContact as $agency) {
            $body = $container->get('templating')->render('Emails/activation_email.html.twig', array(
                'user' => $agency->getName(),
                'text' => $email->getEmailText(),
            ));
            $message = \Swift_Message::newInstance()
                ->setSubject($email->getEmailSubject())
                ->setFrom($mailer_user)
                ->setTo($agency->getEmail())
                ->setCharset('UTF-8')
                ->setContentType('text/html')
                ->setBody($body);
            $mailer->send($message);

        }
        /*--------------- //TODO No contact  21 days after property Upload  End*/
        ############################################################################
        ##                                                                        ##
        ##                        SECTION All every 7 day                         ##
        ##                                                                        ##
        ############################################################################
        /*--------------- //TODO All every 7 da Start*/
        $emailIntervalRepository = $em->getRepository('AppBundle:EmailInterval');

        $query = $emailIntervalRepository->createQueryBuilder('d')
            ->orderBy('d.lastDate','desc')
            ->setMaxResults(1)
            ->getQuery();
        $executeQuery = $query->getSingleResult();

        $lastEmailInterval = $executeQuery->getLastDate()->format('Y-m-d');

        $date = new \DateTime('today');
        $today = $date->format('Y-m-d');

        $agencies = $agencyRepository->findBy(['blockMessage' => false]);

        if ($lastEmailInterval == $today){
            $email = $em->getRepository('AppBundle:EmailReaction')->find(9);
            foreach ($agencies as $agency) {

                $body = $container->get('templating')->render('Emails/activation_email.html.twig', array(
                    'user' => $agency->getName(),
                    'text' => $email->getEmailText(),
                ));
                $message = \Swift_Message::newInstance()
                    ->setSubject($email->getEmailSubject())
                    ->setFrom($mailer_user)
                    ->setTo($agency->getEmail())
                    ->setCharset('UTF-8')
                    ->setContentType('text/html')
                    ->setBody($body);

                $mailer->send($message);
            }
            $dateNow = new \DateTime('+ 7 day');
            $emailInterval = new EmailInterval();
            $emailInterval->setLastDate($dateNow);
            $em->persist($emailInterval);
            $em->flush();
        }
        /*--------------- //TODO All every 7 da End*/
        ############################################################################
        ##                                                                        ##
        ##             SECTION No coin 1 day after budget finished                ##
        ##                                                                        ##
        ############################################################################
        /*--------------- //TODO No coin 1 day after budget finished  Start*/

        $noCoinOneDayQuery = $agencyRepository->createQueryBuilder('a')
            ->where('a.noCoin = :true and a.totalBudgetLimitAt < :date and a.totalBudgetLimitAt > :date2')
            ->andWhere('a.blockMessage = :false')
            ->setParameters(
                [
                    'true' => true,
                    'false' => false,
                    'date' => new \DateTime('- 1 day'),
                    'date2' => new \DateTime('- 2 day'),
                ]
            )
            ->getQuery()
            ;

        $noCoinOneDay = $noCoinOneDayQuery->getResult();

        $email = $em->getRepository('AppBundle:EmailReaction')->find(14);
        foreach ($noCoinOneDay as $agency) {

            $body = $container->get('templating')->render('Emails/activation_email.html.twig', array(
                'user' => $agency->getName(),
                'text' => $email->getEmailText(),
            ));
            $message = \Swift_Message::newInstance()
                ->setSubject($email->getEmailSubject())
                ->setFrom($mailer_user)
                ->setTo($agency->getEmail())
                ->setCharset('UTF-8')
                ->setContentType('text/html')
                ->setBody($body);

            $mailer->send($message);
            $notification = new Notification();
            $notification->setAgency($agency->getId());
            $notification->setNotificationText($email->getInSystemAlert());
            $em->persist($notification);
            $em->flush();
        };
        ############################################################################
        ##                                                                        ##
        ##             SECTION No coin 6 day after budget finished                ##
        ##                                                                        ##
        ############################################################################
      /*--------------- //TODO No coin 6 day after budget finished  Start*/

        $noCoinSixDayQuery = $agencyRepository->createQueryBuilder('a')
            ->where('a.noCoin = :true and a.totalBudgetLimitAt < :date and a.totalBudgetLimitAt > :date2')
            ->andWhere('a.blockMessage = :false')
            ->setParameters(
                [
                    'true' => true,
                    'false' => false,
                    'date' => new \DateTime('- 6 day'),
                    'date2' => new \DateTime('- 7 day'),
                ]
            )
            ->getQuery()
        ;

        $noCoinSixDay = $noCoinSixDayQuery->getResult();

        $email = $em->getRepository('AppBundle:EmailReaction')->find(15);
        foreach ($noCoinSixDay as $agency) {

            $body = $container->get('templating')->render('Emails/activation_email.html.twig', array(
                'user' => $agency->getName(),
                'text' => $email->getEmailText(),
            ));
            $message = \Swift_Message::newInstance()
                ->setSubject($email->getEmailSubject())
                ->setFrom($mailer_user)
                ->setTo($agency->getEmail())
                ->setCharset('UTF-8')
                ->setContentType('text/html')
                ->setBody($body);

            $mailer->send($message);
            $notification = new Notification();
            $notification->setAgency($agency->getId());
            $notification->setNotificationText($email->getInSystemAlert());
            $em->persist($notification);
            $em->flush();
        };

        $output->writeln('Message Send');

    }

}