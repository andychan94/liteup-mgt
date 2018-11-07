<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10/13/2018
 * Time: 12:04 PM
 */

namespace AppBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateLimitCommand extends Command
{
    protected static $defaultName = 'check:update-limit';

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
        $housesRepository = $em->getRepository('AppBundle:House');
        $now = new \DateTime();
        $query = $hasPlan->createQueryBuilder('h')
            ->join('h.plan', 'p')
            ->where('p.id = :plan_id')
            ->andWhere('h.limitDeadLine < :now')
            ->setParameter('plan_id', 3)
            ->setParameter('now', $now)
            ->getQuery();

        $deadLine = $query->getResult();

        foreach ($deadLine as $key) {

            $key->setLimitBalanceAmount($key->getLimitBasicAmount());
            $key->setLimitCreatedAt($key->getLimitDeadLine());
            $dayAfterFlush = $key->getLimitDeadLine()->format("Y-m-d h:i:s");
            $x = Date("Y-m-d h:i:s", strtotime("{$dayAfterFlush} +  {$key->getLimitRangeAt()}"));
            $y = new \DateTime("{$x}");
            $key->setLimitDeadLine($y);
            $key->setActive(true);

            $em->flush();
//
//            $housesQuery = $housesRepository->createQueryBuilder('h')
//                ->where('h.agency = :agency')
//                ->setParameter('agency', $key->getUser())
//                ->getQuery();
//
//            $houses = $housesQuery->getResult();

            $qb = $em->createQueryBuilder();
            $q = $qb->update('AppBundle:House', 'h')
                ->set('h.deactivate', ':false')
                ->where('h.agency = :agency')
                ->setParameter('false', false)
                ->setParameter('agency', $key->getUser())
                ->getQuery();
            $p = $q->execute();
        }

        $output->writeln([
            'Limit updated successfully',
        ]);
    }

}