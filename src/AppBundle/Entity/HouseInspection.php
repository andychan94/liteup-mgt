<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10/16/2018
 * Time: 4:03 PM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="house_inspection")
 */
class HouseInspection
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="houseInspection")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\House", inversedBy="houseInspection")
     */
    private $house;

    /**
     * @ORM\Column(type="string")
     * @Assert\EqualTo(
     *      value = {"Rent","Short","Buy"}
     * )
     */
    private $availableType;

    /**
     * @ORM\Column(type="datetime")
     */
    private $requestedAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $answeredAt = null;

    /**
     * @ORM\Column(type="boolean")
     */
    private $accept = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $dismiss = false;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
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
    public function getRequestedAt()
    {
        return $this->requestedAt;
    }

    /**
     * @param mixed $requestedAt
     */
    public function setRequestedAt($requestedAt)
    {
        $this->requestedAt = $requestedAt;
    }

    /**
     * @return mixed
     */
    public function getAnsweredAt()
    {
        return $this->answeredAt;
    }

    /**
     * @param mixed $answeredAt
     */
    public function setAnsweredAt($answeredAt)
    {
        $this->answeredAt = $answeredAt;
    }

    /**
     * @return mixed
     */
    public function getAccept()
    {
        return $this->accept;
    }

    /**
     * @param mixed $accept
     */
    public function setAccept($accept)
    {
        $this->accept = $accept;
    }

    /**
     * @return mixed
     */
    public function getDismiss()
    {
        return $this->dismiss;
    }

    /**
     * @param mixed $dismiss
     */
    public function setDismiss($dismiss)
    {
        $this->dismiss = $dismiss;
    }

}