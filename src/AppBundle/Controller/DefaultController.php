<?php

namespace AppBundle\Controller;

use Exception;
use FOS\UserBundle\Mailer\MailerInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DefaultController
 * @package AppBundle\Controller
 */
class DefaultController extends BaseController
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * DefaultController constructor.
     * @param MailerInterface $mailer
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }
    /**
     * @Route("/", name="homepage")
     * @param UserManagerInterface $userManager
     * @param \FOS\UserBundle\Mailer\MailerInterface $mailer
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(UserManagerInterface $userManager)
    {
        $user = $userManager->findUserByEmail("nrashidov@yahoo.com");
        if (is_null($user)) {return $this->redirectToRoute('fos_user_registration_register');}
        $this->mailer->sendConfirmationEmailMessage($user);
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/tos", name="tos")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tosAction()
    {
        // replace this example code with whatever you need
        return $this->render(':default/Tos:tos.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/resend-email/{token}", name="resend_email")
     * @param $token
     * @param LoggerInterface $logger
     * @param UserManagerInterface $userManager
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
public function resendActivationEmail($token, LoggerInterface $logger, UserManagerInterface $userManager, Request $request)
{
    $user = $userManager->findUserByConfirmationToken($token);
    if (is_null($user)) {return $this->redirectToRoute('fos_user_registration_register');}
    $this->mailer->sendConfirmationEmailMessage($user);
    try {
        $this->mailer->sendConfirmationEmailMessage($user);
    } catch (Exception $e) {
        $this->logger($logger, $e->getMessage());
    }
    return $this->redirectToRoute('fos_user_security_login');
}


    /**
     * @param LoggerInterface $logger
     * @param String $message
     * @param String $option
     */
    protected function logger(LoggerInterface $logger, $message, $option = null)
    {
        $logger->error(
            $message .
            "\nUser id: " . $this->getUser()->getId() .
            "\nUser email: " . $this->getUser()->getEmail() .
            "\n" . $option
        );
    }
}
