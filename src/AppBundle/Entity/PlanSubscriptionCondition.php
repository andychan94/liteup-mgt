<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10/5/2018
 * Time: 1:03 PM
 */

namespace AppBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="plan_subscription_condition")
 */
class PlanSubscriptionCondition
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\PlanSubscription", mappedBy="planCondition", fetch="EXTRA_LAZY")
     */
    private $plan;

    /**
     * @ORM\Column(type="integer")
     */
    private $conditionOrder;

    /**
     * @ORM\Column(type="string")
     */
    private $conditionTitle;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function __construct()
    {
        return  $this->plan = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getConditionOrder()
    {
        return $this->conditionOrder;
    }

    /**
     * @param mixed $conditionOrder
     */
    public function setConditionOrder($conditionOrder)
    {
        $this->conditionOrder = $conditionOrder;
    }

    /**
     * @return mixed
     */
    public function getConditionTitle()
    {
        return $this->conditionTitle;
    }

    /**
     * @param mixed $conditionTitle
     */
    public function setConditionTitle($conditionTitle)
    {
        $this->conditionTitle = $conditionTitle;
    }

    /**
     * @return mixed
     */
    public function getPlan()
    {
        return $this->plan;
    }

    /**
     * @param mixed $plan
     */
    public function setPlan($plan)
    {
        $this->plan = $plan;
    }

}