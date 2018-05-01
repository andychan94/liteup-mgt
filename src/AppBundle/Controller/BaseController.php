<?php

namespace AppBundle\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
    /**
     * @var $entityName String
     */
    protected $entityName;
    /**
     * @var $entityAltName String
     */
    protected $entityAltName;
    /**
     * @var $objName String
     */
    protected $objName;

    /**
     * @param $key
     * @param array $params
     * @return string
     */
    public function t($key, $params = array())
    {
        return $this->get('translator')->trans(
            $key,
            $params
        );
    }

    /**
     * @param LoggerInterface $logger
     * @param String $message
     * @param String $option
     */
    public function logger(LoggerInterface $logger, $message, $option = null)
    {
        $logger->error(
            $message .
            "\nUser id: " . $this->getUser()->getId() .
            "\nUser email: " . $this->getUser()->getEmail() .
            "\n" . $option
        );
    }
}
