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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PageController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $states = $this->getDoctrine()->getRepository('AppBundle:State')->findBy([],['name'=> 'ASC']);
        $slider = $this->getDoctrine()->getRepository('AppBundle:Slider')->find(1);
        $aboutUs = $this->getDoctrine()->getRepository('AppBundle:Page')->find(1);
        $blogs = $this->getDoctrine()->getRepository('AppBundle:Blog')->findBy([],['blogCreatedAt' => 'DESC'],3);
        $housesRepository = $this->getDoctrine()->getRepository('AppBundle:House');
        $housesQuery = $housesRepository->createQueryBuilder('h')
            ->where('h.isDeleted = 0')
            ->andWhere('h.deactivate = 0')
            ->andWhere('h.isAvailable = 1')
            ->andWhere('h.showOnTop = 1')
            ->setMaxResults(15)
            ->getQuery();

        $houses = $housesQuery->getResult();


        $roomCount = array(
             "0"=> 'ZERO',
             "1"=> 'ONE',
             "2"=> 'TWO',
             "3"=> 'THREE',
             "4"=> 'FOUR',
             "5"=> 'FIVE',
             "6"=> 'SIX',
             "7"=> 'SEVENPLUS',
        );

        return $this->render('pages/index.html.twig', [
            'slider' => $slider,
            'states' => $states,
            'aboutUs' => $aboutUs,
            'blogs' => $blogs,
            'houses' => $houses,
            'roomCount' => $roomCount,
        ]);
    }

    public function followUsAction(){
        $followUsRepository = $this->getDoctrine()->getRepository('AppBundle:FollowUs');

        $query = $followUsRepository->createQueryBuilder('f')
            ->where('f.id != 7')
            ->orderBy('f.followOrder','ASC')
            ->getQuery()
            ;
        $followUs = $query->getResult();
        return $this->render('parts/footer_follow_us.html.twig', array(
            'followUs' => $followUs
        ));
    }

    public function helpSupportAction(){
        $helpSupports = $this->getDoctrine()->getRepository('AppBundle:HelpSupport')->findBy([],['helpSupportOrder' => 'ASC']);

        return $this->render('parts/help-support-order.html.twig',array(
            'helpSupports' => $helpSupports,
        ));
    }

    public function playMarketAction(){
        $playMarket = $this->getDoctrine()->getRepository('AppBundle:FollowUs')->find(7);

        return $this->render('parts/play-market.html.twig',array(
            'playMarket' => $playMarket
        ));
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