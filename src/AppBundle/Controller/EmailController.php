<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10/22/2018
 * Time: 12:20 PM
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EmailController extends Controller
{
    /**
     * @Route("registration-email/", name="registration_email")
     */
    public function registrationEmailAction( Request $request){
       $email =  $this->getDoctrine()->getRepository('AppBundle:Email')->find(1);
        $userName = $request->get('userName');
        $confirmationUrl = $request->get('confirmationUrl');
        return $this->render('Emails/sign_up_text_content.html.twig',
            array(
                'email' => $email,
                'userName' => $userName,
                'confirmationUrl' => $confirmationUrl
            ));
    }
}