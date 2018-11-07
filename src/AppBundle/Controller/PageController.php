<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 9/26/2018
 * Time: 11:22 AM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\ContactMessage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PageController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $slider = $this->getDoctrine()->getRepository('AppBundle:Slider')->find(1);
        return $this->render('pages/index.html.twig', [
            'slider' => $slider,
        ]);
    }


    /**
     * @Route("/contact-us", name="contact_page")
     */
    public function contactAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $page = $em->getRepository('AppBundle:Page')->find(2);

        $name = $request->get('name');
        $email = $request->get('email');
        $message = $request->get('message');

        if (
            !empty($name) &&
            !empty($email) &&
            !empty($message)
        ) {

            $contactMessage = new ContactMessage();
            $contactMessage->setName($name);
            $contactMessage->setEmail($email);
            $contactMessage->setMessage($message);
            $em->persist($contactMessage);
            $em->flush();
            $this->addFlash('success', 'Message successfully sent.');
            return $this->redirectToRoute('contact_page');
        }

        $contacts = $this->getDoctrine()->getRepository('AppBundle:Contact')->findBy([], ['contactOrder' => 'ASC']);

        return $this->render('pages/contact.html.twig',
            array(
                'contacts' => $contacts,
                'page' => $page,
            )
        );
    }

    /**
     * @Route("/about-us", name="about_page")
     */
    public function aboutAction()
    {
        $page = $this->getDoctrine()->getRepository('AppBundle:Page')->find(1);
        return $this->render('pages/about.html.twig',
            array(
                'page' => $page,
            )
        );
    }
}