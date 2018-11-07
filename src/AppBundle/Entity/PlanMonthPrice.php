<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10/6/2018
 * Time: 1:36 PM
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="plan_month_price")
 */
class PlanMonthPrice
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
    private $price;

    /**
     * @ORM\Column(type="integer")
     */
    private $monthCount;

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
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getMonthCount()
    {
        return $this->monthCount;
    }

    /**
     * @param mixed $monthCount
     */
    public function setMonthCount($monthCount)
    {
        $this->monthCount = $monthCount;
    }
}