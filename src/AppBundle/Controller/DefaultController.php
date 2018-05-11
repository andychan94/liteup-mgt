<?php

namespace AppBundle\Controller;

use Exception;
use FOS\UserBundle\Model\UserManagerInterface;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends BaseController
{
    /**
     * @Route("/", name="homepage")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
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
    $mailer = $this->get('fos_user.mailer');
    try {
        $mailer->sendConfirmationEmailMessage($user);
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
