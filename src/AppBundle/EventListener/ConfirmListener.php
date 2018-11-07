<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10/24/2018
 * Time: 5:22 PM
 */

namespace AppBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ConfirmListener implements EventSubscriberInterface
{
    private $router;
    private $em;
    private $container;

    public function __construct(UrlGeneratorInterface $router, EntityManagerInterface $entityManager,ContainerInterface $container)
    {
        $this->router = $router;
        $this->em = $entityManager;
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_CONFIRM => 'onRegistrationConfirm',
        );
    }

    public function onRegistrationConfirm(GetResponseUserEvent  $getResponseUserEvent)
    {
       $user =  $getResponseUserEvent->getUser();
       $agency =  $this->em->getRepository('AppBundle:Agency')->find($user);
       $agency->setFirstLogin(new \DateTime());
       $this->em->flush();
       $container = $this->container;
        $email = $this->em->getRepository('AppBundle:Email')->find(5);
        $body = $container->get('templating')->render('Emails/activation_email.html.twig', array(
            'user' => $agency->getName(),
            'text' => $email->getEmailText(),
        ));
        $mailer_user = $container->getParameter('mailer_user');
        $message = \Swift_Message::newInstance()
            ->setSubject($email->getEmailSubject())
            ->setFrom($mailer_user)
            ->setTo($agency->getEmail())
            ->setCharset('UTF-8')
            ->setContentType('text/html')
            ->setBody($body);
        $container->get('mailer')->send($message);
       $this->router->generate('dashboard_home');
    }
}