<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/23/2018
 * Time: 4:23 PM
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="contact_count_by_price")
 */
class ContactCountByPrice
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
    private $minSalePrice;

    /**
     * @ORM\Column(type="integer")
     */
    private $maxSalePrice;

    /**
     * @ORM\Column(type="integer")
     */
    private $minRentPrice;

    /**
     * @ORM\Column(type="integer")
     */
    private $maxRentPrice;

    /**
     * @ORM\Column(type="integer")
     */
    private $minShortPrice;

    /**
     * @ORM\Column(type="integer")
     */
    private $maxShortPrice;

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
    public function getMinSalePrice()
    {
        return $this->minSalePrice;
    }

    /**
     * @param mixed $minSalePrice
     */
    public function setMinSalePrice($minSalePrice)
    {
        $this->minSalePrice = $minSalePrice;
    }

    /**
     * @return mixed
     */
    public function getMaxSalePrice()
    {
        return $this->maxSalePrice;
    }

    /**
     * @param mixed $maxSalePrice
     */
    public function setMaxSalePrice($maxSalePrice)
    {
        $this->maxSalePrice = $maxSalePrice;
    }

    /**
     * @return mixed
     */
    public function getMinRentPrice()
    {
        return $this->minRentPrice;
    }

    /**
     * @param mixed $minRentPrice
     */
    public function setMinRentPrice($minRentPrice)
    {
        $this->minRentPrice = $minRentPrice;
    }

    /**
     * @return mixed
     */
    public function getMaxRentPrice()
    {
        return $this->maxRentPrice;
    }

    /**
     * @param mixed $maxRentPrice
     */
    public function setMaxRentPrice($maxRentPrice)
    {
        $this->maxRentPrice = $maxRentPrice;
    }

    /**
     * @return mixed
     */
    public function getMinShortPrice()
    {
        return $this->minShortPrice;
    }

    /**
     * @param mixed $minShortPrice
     */
    public function setMinShortPrice($minShortPrice)
    {
        $this->minShortPrice = $minShortPrice;
    }

    /**
     * @return mixed
     */
    public function getMaxShortPrice()
    {
        return $this->maxShortPrice;
    }

    /**
     * @param mixed $maxShortPrice
     */
    public function setMaxShortPrice($maxShortPrice)
    {
        $this->maxShortPrice = $maxShortPrice;
    }

}