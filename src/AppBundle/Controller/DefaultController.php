<?php

namespace AppBundle\Controller;

use Exception;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends BaseController
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
    /**
     * @Route("/tos", name="tos")
     */
    public function tosAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render(':default/Tos:tos.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
    /**
     * @Route("/resend-email", name="resend_email")
     */
    public function resendActivationEmail(LoggerInterface $logger)
    {
        $user = $this->getUser();
        $mailer = $this->get('fos_user.mailer');
        try {
            $mailer->sendConfirmationEmailMessage($user);
        } catch (Exception $e) {
            $this->logger($logger, $e->getMessage());
        }
        return $this->redirectToRoute('fos_user_registration_check_email');
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
