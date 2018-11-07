<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10/30/2018
 * Time: 4:17 PM
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NotificationController extends Controller
{
    /**
     * @Route("/notifications/{paginate}", name="notifications")
     */
    public function notificationsActions($paginate = 1)
    {

        $notifications = $this->getDoctrine()->getRepository('AppBundle:Notification');

        $postsPerPage = 20;
        $paginationTotal = 1;

        $offset = $paginate - 1;
        if ($paginate !== 1) {
            $offset = $postsPerPage * $paginate - $postsPerPage;
        }

        $notificationTotalCountQuery = $notifications->createQueryBuilder('n')
            ->select('count(n)')
            ->where('n.agency = :agency')
            ->orderBy('n.createdAt', 'DESC')
            ->setParameter('agency', $this->getUser()->getId())
            ->getQuery();
        $notificationTotalCount = $notificationTotalCountQuery->getSingleScalarResult();

        $paginationTotal = ceil($notificationTotalCount / $postsPerPage);

        $notificationQuery = $notifications->createQueryBuilder('n')
            ->where('n.agency = :agency')
            ->addOrderBy('n.isCheck', 'ASC')
            ->addOrderBy('n.createdAt', 'DESC')
            ->setParameter('agency', $this->getUser()->getId())
            ->setFirstResult($offset)
            ->setMaxResults($postsPerPage)
            ->getQuery();
        $notifications = $notificationQuery->getResult();

        return $this->render('dashboard/history/notification.html.twig', array(
            'notifications' => $notifications,
            'paginate' => $paginate,
            'paginationTotal' => $paginationTotal
        ));
    }

    public function notificationCountAction()
    {

        $notifications = $this->getDoctrine()->getRepository('AppBundle:Notification');
        $notificationTotalCountQuery = $notifications->createQueryBuilder('n')
            ->select('count(n)')
            ->where('n.agency = :agency and n.isCheck = :false')
            ->setParameter('agency', $this->getUser()->getId())
            ->setParameter('false', 0)
            ->getQuery();

        $notificationTotalCount = $notificationTotalCountQuery->getSingleScalarResult();

        return $this->render('dashboard/history/notification_count.html.twig',
            array(
                'notificationCount' => $notificationTotalCount
            ));
    }
}