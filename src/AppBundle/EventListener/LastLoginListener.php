<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 9/20/2018
 * Time: 10:53 AM
 */

namespace AppBundle\EventListener;


use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class LastLoginListener implements EventSubscriberInterface
{

    private $tokenStorage;
    private $authenticationUtils;
    protected $router;
    protected $dispatcher;

    public function __construct(TokenStorageInterface $tokenStorage, AuthenticationUtils $authenticationUtils,UrlGeneratorInterface $router, EventDispatcherInterface $dispatcher)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationUtils = $authenticationUtils;
        $this->router = $router;
        $this->dispatcher = $dispatcher;
    }

    public static function getSubscribedEvents()
    {
        return array(
//            AuthenticationEvents::AUTHENTICATION_FAILURE => 'onAuthenticationFailure',
            SecurityEvents::INTERACTIVE_LOGIN => 'onSecurityInteractiveLogin',
        );
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $this->tokenStorage->getToken()->getUser();
        if ($user->getDeactivate() == true){
            $this->dispatcher->addListener(KernelEvents::RESPONSE, array($this, 'onKernelResponse'));
        }
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $response = new RedirectResponse($this->router->generate('reactivate_request'));

        $event->setResponse($response);
    }
}