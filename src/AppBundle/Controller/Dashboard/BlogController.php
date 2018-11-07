<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 9/28/2018
 * Time: 1:07 PM
 */

namespace AppBundle\Controller\Dashboard;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BlogController extends Controller
{
    /**
     * @Route("/blog-dashboard", name="blog_dashboard")
     */
    public function blogDashboardAction(){

        return $this->render('dashboard/blog/index.html.twig');
    }
}