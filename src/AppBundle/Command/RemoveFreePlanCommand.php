<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/16/2018
 * Time: 4:44 PM
 */

namespace AppBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RemoveFreePlanCommand extends Command
{

    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('app:remove-free-plan')

            // the short description shown while running "php bin/console list"
            ->setDescription('Creates a new user.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to create a user...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->em;
        $agencyPlanRepository = $em->getRepository('AppBundle:UserPlan');
        if (new \DateTime() > new \DateTime('2019-01-01 00:00:00')){

        $query = $agencyPlanRepository->createQueryBuilder('p')
            ->where('p.freePlanActive = :active')
            ->andWhere('p.planDeadLine = :date')
            ->setParameter('active',true)
            ->setParameter('date',new \DateTime('2019-01-01 00:00:00'))
            ->getQuery()
            ;
        $agencyPlanes = $query->getResult();

        foreach ($agencyPlanes as $plan){
            $em->remove($plan);
            $em->flush();
        }
        $output->write('Plans  successfully removed');
        }
        $output->write('Success');
    }

}