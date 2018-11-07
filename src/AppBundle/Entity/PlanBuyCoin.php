<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 9/17/2018
 * Time: 3:27 PM
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 *@ORM\Entity
 * @ORM\Table(name="plan_buy_coin")
 */
class PlanBuyCoin
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $planTitle;

    /**
     * @ORM\Column(type="text")
     */
    private $planDescription;


    /**
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $planPrice;

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
    public function getPlanTitle()
    {
        return $this->planTitle;
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
    public function getPlanDescription()
    {
        return $this->planDescription;
    }

    /**
     * @param mixed $planDescription
     */
    public function setPlanDescription($planDescription)
    {
        $this->planDescription = $planDescription;
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

    public function __construct()
    {
        $this->planPrice = ArrayCollection::class;
    }


}