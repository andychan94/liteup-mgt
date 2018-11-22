<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10/10/2018
 * Time: 6:43 PM
 */

namespace AppBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LandlordPackCommand extends Command
{
    protected static $defaultName = 'app:landlord-pack';

    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->em;
        $hasPlan = $em->getRepository('AppBundle:UserPlan');
        $now = new \DateTime();
        $x = $hasPlan->createQueryBuilder('h')
            ->join('h.plan', 'p')
            ->where('p.id = :plan_id')
            ->andWhere('h.planDeadLine < :now')
            ->setParameter('plan_id', 1)
            ->setParameter('now', $now)
            ->getQuery();
        $deadLine = $x->getResult();

        foreach ($deadLine as $key){

            $em->createQueryBuilder('h')
                ->update('AppBundle:House','h')
                ->set('h.deactivate', ':num')
                ->where('h.agency = :agency')
                ->setParameter(':num', 1)
                ->setParameter(':agency', $key->getUser())
                ->getQuery()
                ->execute();

            $em->createQueryBuilder('h')
                ->update('AppBundle:UserPlan','p')
                ->set('p.planCreatedAt', ':null')
                ->set('p.planDeadLine', ':null')
                ->set('p.active', 0)
                ->where('p.user = :user')
                ->setParameter(':user', $key->getUser())
                ->setParameter(':null', null)
                ->getQuery()
                ->execute();
            $this->em->flush();
        }

        $output->writeln([
            'Plan Landlord Pack updated successfully',
        ]);
    }

}