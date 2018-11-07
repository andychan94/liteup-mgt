<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10/5/2018
 * Time: 12:41 PM
 */

namespace AppBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="plan_subscription")
 */
class PlanSubscription
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $planOrder;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\PlanSubscriptionCondition", inversedBy="planCondition", fetch="EXTRA_LAZY")
     */
    private $planCondition;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\UserPlan", mappedBy="plan")
     */
    private $userPlan;

    /**
     * @ORM\Column(type="string")
     */
    private $planTitle;

    /**
     * @ORM\Column(type="string")
     */
    private $planPrice;

    /**
     * @ORM\Column(type="text")
     */
    private $planText;

    /**
     * @ORM\Column(type="json_array")
     */
    private $planAnyMonth;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function __construct()
    {
        return $this->planCondition = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getPlanTitle()
    {
        return $this->planTitle;
    }

    /**
     * @return mixed
     */
    public function getPlanOrder()
    {
        return $this->planOrder;
    }

    /**
     * @param mixed $planOrder
     */
    public function setPlanOrder($planOrder)
    {
        $this->planOrder = $planOrder;
    }

    /**
     * @param mixed $planTitle
     */
    public function setPlanTitle($planTitle)
    {
        $this->planTitle = $planTitle;
    }

    /**
     * @return mixed
     */
    public function getPlanPrice()
    {
        return $this->planPrice;
    }

    /**
     * @param mixed $planPrice
     */
    public function setPlanPrice($planPrice)
    {
        $this->planPrice = $planPrice;
    }

    /**
     * @return mixed
     */
    public function getPlanCondition()
    {
        return $this->planCondition;
    }

    /**
     * @param mixed $planCondition
     */
    public function setPlanCondition($planCondition)
    {
        $this->planCondition = $planCondition;
    }

    /**
     * @return mixed
     */
    public function getPlanAnyMonth()
    {
        return $this->planAnyMonth;
    }

    /**
     * @param mixed $planAnyMonth
     */
    public function setPlanAnyMonth($planAnyMonth)
    {
        $this->planAnyMonth = $planAnyMonth;
    }

    /**
     * @return mixed
     */
    public function getUserPlan()
    {
        return $this->userPlan;
    }

    /**
     * @param mixed $userPlan
     */
    public function setUserPlan($userPlan)
    {
        $this->userPlan = $userPlan;
    }

    /**
     * @return mixed
     */
    public function getPlanText()
    {
        return $this->planText;
    }

    /**
     * @param mixed $planText
     */
    public function setPlanText($planText)
    {
        $this->planText = $planText;
    }

}