<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10/18/2018
 * Time: 3:09 PM
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_house")
 */
class UserHouse
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="userHouse")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\UserHouseRequest", mappedBy="house")
     */
    private $house;

    /**
     * @ORM\Column(type="string")
     */
    private $houseTitle;

    /**
     * @ORM\Column(type="string")
     */
    private $houseAddress;

    /**
     * @ORM\Column(type="text")
     */
    private $houseDescription;

    /**
     * @ORM\Column(type="integer")
     */
    private $housePrice;

    /**
     * @ORM\Column(type="string")
     */
    private $availableType;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

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
    public function getHouseTitle()
    {
        return $this->houseTitle;
    }

    /**
     * @param mixed $houseTitle
     */
    public function setHouseTitle($houseTitle)
    {
        $this->houseTitle = $houseTitle;
    }

    /**
     * @return mixed
     */
    public function getHouseAddress()
    {
        return $this->houseAddress;
    }

    /**
     * @param mixed $houseAddress
     */
    public function setHouseAddress($houseAddress)
    {
        $this->houseAddress = $houseAddress;
    }

    /**
     * @return mixed
     */
    public function getHouseDescription()
    {
        return $this->houseDescription;
    }

    /**
     * @param mixed $houseDescription
     */
    public function setHouseDescription($houseDescription)
    {
        $this->houseDescription = $houseDescription;
    }

    /**
     * @return mixed
     */
    public function getHousePrice()
    {
        return $this->housePrice;
    }

    /**
     * @param mixed $housePrice
     */
    public function setHousePrice($housePrice)
    {
        $this->housePrice = $housePrice;
    }

    /**
     * @return mixed
     */
    public function getAvailableType()
    {
        return $this->availableType;
    }

    /**
     * @param mixed $availableType
     */
    public function setAvailableType($availableType)
    {
        $this->availableType = $availableType;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getHouse()
    {
        return $this->house;
    }

    /**
     * @param mixed $house
     */
    public function setHouse($house)
    {
        $this->house = $house;
    }


}