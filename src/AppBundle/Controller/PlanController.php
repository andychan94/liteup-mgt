<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 9/21/2018
 * Time: 5:50 PM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\UserPlan;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PlanController extends Controller
{

    /**
     * @Route("/subscription-plan", name="subscription_plan")
     */
    public function subscriptionPlanAction()
    {
        $plansRepository = $this->getDoctrine()->getRepository('AppBundle:PlanSubscription');
        $planMonthPrices = $this->getDoctrine()->getRepository('AppBundle:PlanMonthPrice')->findAll();

        $query = $plansRepository->createQueryBuilder('p')
            ->addOrderBy('p.planOrder', 'ASC')
            ->getQuery();

        $plans = $query->getResult();
        return $this->render('dashboard/plan/subscription-plan.html.twig',
            array(
                'plans' => $plans,
                'planMonthPrices' => $planMonthPrices,
            ));
    }


    /**
     * @Route("/landlord-pack/{slug}", name="landlord_pack")
     */
    public function landlordPackAction(Request $request, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        $selectedPlan = $em->getRepository('AppBundle:PlanSubscription')->find($slug);

        $activeUser = $em->getRepository('AppBundle:Agency')->find($this->getUser());

        $planPrice = $selectedPlan->getPlanPrice();

        $userActualBudget = $activeUser->getBudget();

        $userPlanRepository = $em->getRepository('AppBundle:UserPlan');

        $hasPlan = $userPlanRepository->findOneBy(['user' => $activeUser]);

        if ($userActualBudget >= $planPrice) {

            $newUserPlan = new UserPlan();

            if ($hasPlan == null) {

                $newUserPlan->setUser($activeUser);
                $newUserPlan->setPlan($selectedPlan);
                $newUserPlan->setPlanCreatedAt(new \DateTime());
                $newUserPlan->setPlanDeadLine(new \DateTime('+ 1 month'));
                $newUserPlan->setActive(true);
                $activeUser->setBudget($userActualBudget - $planPrice);
                $em->persist($newUserPlan);

            } else {

                $hasPlan->setPlan($selectedPlan);
                $hasPlan->setPlanCreatedAt(new \DateTime());
                $hasPlan->setPlanDeadLine(new \DateTime('+ 1 month'));
                $hasPlan->setActive(true);
                $hasPlan->setLimitBasicAmount(null);
                $hasPlan->setLimitBalanceAmount(null);
                $hasPlan->setLimitCreatedAt(null);
                $hasPlan->setLimitDeadLine(null);
                $hasPlan->setLimitRangeAt(null);
                $hasPlan->setFreePlanActive(false);
                $activeUser->setBudget($userActualBudget - $planPrice);
            }

            $em->flush();

            $houses = $em->getRepository('AppBundle:House')->findBy(['agency' => $activeUser], ['createdAt' => 'DESC']);
            $index = 0;
            foreach ($houses as $house) {
                $index++;
                if ($index <= 3) {
                    $house->setDeactivate(0);
                }else{

                    $house->setDeactivate(1);
                }
                $em->flush();
            }

            return $this->redirectToRoute('subscription_plan');
        }

        $this->addFlash('error', 'Less money please buy coins');
        return $this->redirectToRoute('buy_coins_dashboard');

    }


    /**
     * @Route("/standard-pack/{slug}", name="standard_pack")
     */
    public function standardPackAction(Request $request, $slug)
    {
        $limitedMonth = $request->get('month');

        $em = $this->getDoctrine()->getManager();

        $selectedPlan = $em->getRepository('AppBundle:PlanSubscription')->find($slug);

        $activeUser = $em->getRepository('AppBundle:Agency')->find($this->getUser());

        if ($limitedMonth == 'default') {

            $planPrice = $selectedPlan->getPlanPrice();
            $monthCount = '1';

        } else {

            $monthRepository = $em->getRepository('AppBundle:PlanMonthPrice')->find($limitedMonth);
            $planPrice = $monthRepository->getPrice();
            $monthCount = $monthRepository->getMonthCount();
        }

        $userActualBudget = $activeUser->getBudget();

        $userPlanRepository = $em->getRepository('AppBundle:UserPlan');

        $hasPlan = $userPlanRepository->findOneBy(['user' => $activeUser]);

        if ($userActualBudget >= $planPrice) {

            $newUserPlan = new UserPlan();

            if ($hasPlan == null) {

                $newUserPlan->setUser($activeUser);
                $newUserPlan->setPlan($selectedPlan);
                $newUserPlan->setActive(true);
                $newUserPlan->setPlanCreatedAt(new \DateTime());
                $newUserPlan->setPlanDeadLine(new \DateTime("+  {$monthCount} month"));
                $activeUser->setBudget($userActualBudget - $planPrice);
                $em->persist($newUserPlan);

            } else {
                $hasPlan->setPlan($selectedPlan);
                $hasPlan->setPlanCreatedAt(new \DateTime());
                $hasPlan->setPlanDeadLine(new \DateTime("+  {$monthCount} month"));
                $hasPlan->setActive(true);
                $hasPlan->setLimitBasicAmount(null);
                $hasPlan->setLimitBalanceAmount(null);
                $hasPlan->setLimitCreatedAt(null);
                $hasPlan->setLimitDeadLine(null);
                $hasPlan->setLimitRangeAt(null);
                $hasPlan->setFreePlanActive(false);
                $activeUser->setBudget($userActualBudget - $planPrice);

            }
            $em->flush();
            $houses = $em->getRepository('AppBundle:House')->findBy(['agency' => $activeUser], []);

            foreach ($houses as $house) {
                $house->setDeactivate(0);
                $em->flush();
            }

            return $this->redirectToRoute('subscription_plan');
        }
        $this->addFlash('error', 'Less coins please buy coins');
        return $this->redirectToRoute('buy_coins_dashboard');
    }


    /**
     * @Route("/no-risk-plan-pack/{slug}", name="no_risk_plan")
     */
    public function noRiskPlanAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $selectedPlan = $em->getRepository('AppBundle:PlanSubscription')->find($slug);

        $activeUser = $em->getRepository('AppBundle:Agency')->find($this->getUser());

        $userPlanRepository = $em->getRepository('AppBundle:UserPlan');

        $hasPlan = $userPlanRepository->findOneBy(['user' => $activeUser]);

        $newUserPlan = new UserPlan();

        if ($hasPlan == null) {

            $newUserPlan->setUser($activeUser);
            $newUserPlan->setPlan($selectedPlan);
            $newUserPlan->setPlanCreatedAt(new \DateTime());
            $newUserPlan->setActive(true);
            $newUserPlan->setPlanDeadLine(null);
            $em->persist($newUserPlan);

        } else {

            $hasPlan->setPlan($selectedPlan);
            $hasPlan->setActive(true);
            $hasPlan->setPlanCreatedAt(new \DateTime());
            $hasPlan->setFreePlanActive(false);
            $hasPlan->setPlanDeadLine(null);

        }
        $em->flush();
        $houses = $em->getRepository('AppBundle:House')->findBy(['agency' => $activeUser], []);

        foreach ($houses as $house) {
            $house->setDeactivate(0);
            $em->flush();
        }

        return $this->redirectToRoute('subscription_plan');

    }


    /**
     * @Route("/manage-limit-bidget", name="manage_limit_budget")
     */
    public function manageLimitBudgetAction(Request $request)
    {
        $amount = $request->get('amount');



        $limitRangeAt = $request->get('limitRangeAt');

        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();
        if ($user->getBudget() < $amount){
            $this->addFlash('error','Your budget less then selected amount value');
            return $this->redirectToRoute('buy_coins_dashboard');
        }
        $userPlanRepository = $em->getRepository('AppBundle:UserPlan');

        $hasPlan = $userPlanRepository->findOneBy(['user' => $user]);

        $hasPlan->setLimitBasicAmount($amount);
        $hasPlan->setLimitBalanceAmount($amount);
        $hasPlan->setLimitCreatedAt(new \DateTime());
        $hasPlan->setLimitDeadLine(new \DateTime("+  {$limitRangeAt}"));
        $hasPlan->setLimitRangeAt($limitRangeAt);
        $em->flush();

        return $this->redirectToRoute('buy_coins_dashboard');

    }

    /**
     * @Route("/remove-limit-amount", name="remove_limit")
     */
    public function removeLimitAmountAction()
    {

        $em = $this->getDoctrine()->getManager();

        $userPlan = $em->getRepository("AppBundle:UserPlan")->findOneBy(['user' => $this->getUser()]);
        $userPlan->setLimitBasicAmount(null);
        $userPlan->setLimitBalanceAmount(null);
        $userPlan->setLimitCreatedAt(null);
        $userPlan->setLimitDeadLine(null);
        $userPlan->setLimitRangeAt(null);
        $em->flush();
        $query = $em->createQueryBuilder()
            ->update('AppBundle:House', 'h')
            ->set('h.deactivate', ':false')
            ->where('h.agency = :agency')
            ->setParameter('agency', $this->getUser())
            ->setParameter('false', false)
            ->getQuery();
        $query->execute();
        return $this->redirectToRoute('buy_coins_dashboard');
    }

    /**
     * @Route("/buy-coins/dashboard", name="buy_coins_dashboard")
     */
    public function planeBuyCoinsAction()
    {

        $plan = $this->getDoctrine()->getRepository('AppBundle:PlanBuyCoin')->find(1);

        return $this->render('dashboard/pay/buy-coin.html.twig',
            array(
                'plan' => $plan
            ));
    }

    /**
     * @Route("/transaction-history", name="transaction_history")
     */
    public function transactionHistoryController()
    {

        $transactions = $this->getDoctrine()->getRepository("AppBundle:PaymentOrder")->findBy(['user' => $this->getUser()], ['transactionDate' => 'DESC']);

        return $this->render('dashboard/pay/transaction-history.html.twig',
            array(
                'transactions' => $transactions
            ));

    }

}