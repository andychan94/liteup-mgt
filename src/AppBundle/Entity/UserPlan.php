<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10/5/2018
 * Time: 6:24 PM
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_plan")
 */
class UserPlan
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Agency", inversedBy="userPlan", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\PlanSubscription", inversedBy="userPlan")
     */
    private $plan;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $planCreatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $planDeadLine;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $limitBasicAmount = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $limitBalanceAmount = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $limitCreatedAt = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $limitDeadLine = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $limitRangeAt = null;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="boolean")
     */
    private $freePlanActive = false;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
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

    /**
     * @return mixed
     */
    public function getPlanCreatedAt()
    {
        return $this->planCreatedAt;
    }

    /**
     * @param mixed $planCreatedAt
     */
    public function setPlanCreatedAt($planCreatedAt)
    {
        $this->planCreatedAt = $planCreatedAt;
    }

    /**
     * @return mixed
     */
    public function getPlanDeadLine()
    {
        return $this->planDeadLine;
    }

    /**
     * @param mixed $planDeadLine
     */
    public function setPlanDeadLine($planDeadLine)
    {
        $this->planDeadLine = $planDeadLine;
    }

    /**
     * @return mixed
     */
    public function getLimitBasicAmount()
    {
        return $this->limitBasicAmount;
    }

    /**
     * @param mixed $limitBasicAmount
     */
    public function setLimitBasicAmount($limitBasicAmount)
    {
        $this->limitBasicAmount = $limitBasicAmount;
    }

    /**
     * @return mixed
     */
    public function getLimitBalanceAmount()
    {
        return $this->limitBalanceAmount;
    }

    /**
     * @param mixed $limitBalanceAmount
     */
    public function setLimitBalanceAmount($limitBalanceAmount)
    {
        $this->limitBalanceAmount = $limitBalanceAmount;
    }

    /**
     * @return mixed
     */
    public function getLimitCreatedAt()
    {
        return $this->limitCreatedAt;
    }

    /**
     * @param mixed $limitCreatedAt
     */
    public function setLimitCreatedAt($limitCreatedAt)
    {
        $this->limitCreatedAt = $limitCreatedAt;
    }

    /**
     * @return mixed
     */
    public function getLimitDeadLine()
    {
        return $this->limitDeadLine;
    }

    /**
     * @param mixed $limitDeadLine
     */
    public function setLimitDeadLine($limitDeadLine)
    {
        $this->limitDeadLine = $limitDeadLine;
    }

    /**
     * @return mixed
     */
    public function getLimitRangeAt()
    {
        return $this->limitRangeAt;
    }

    /**
     * @param mixed $limitRangeAt
     */
    public function setLimitRangeAt($limitRangeAt)
    {
        $this->limitRangeAt = $limitRangeAt;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param mixed $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return mixed
     */
    public function getFreePlanActive()
    {
        return $this->freePlanActive;
    }

    /**
     * @param mixed $freePlanActive
     */
    public function setFreePlanActive($freePlanActive)
    {
        $this->freePlanActive = $freePlanActive;
    }



}