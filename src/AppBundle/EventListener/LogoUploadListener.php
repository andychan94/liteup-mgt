<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10/26/2018
 * Time: 5:51 PM
 */

namespace AppBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class LogoUploadListener implements EventSubscriberInterface
{
    private $router;
    private $em;
    private $container;
    private $tokenStorage;

    public function __construct(UrlGeneratorInterface $router,
                                EntityManagerInterface $entityManager,
                                ContainerInterface $container,
                                \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage)
    {
        $this->router = $router;
        $this->em = $entityManager;
        $this->container = $container;
        $this->tokenStorage = $tokenStorage;

    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::PROFILE_EDIT_SUCCESS => 'onProfileEdit',
        );
    }

    public function onProfileEdit(FormEvent $event)
    {
        $user = $this->tokenStorage->getToken()->getUser();
        $agencyLogo = $event->getRequest()->files->get('fos_user_profile_form');
        $logo = $agencyLogo['imageFile']['file'];

        if ($logo != null) {

            $agency = $this->em->getRepository('AppBundle:Agency')->find($user);
            $agency->setFirstLogin(new \DateTime());
            $this->em->flush();
            $container = $this->container;
            $email = $this->em->getRepository('AppBundle:Email')->find(9);
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
        }
    }
}