<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10/16/2018
 * Time: 5:24 PM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @ORM\Table(name="call_request")
 */
class CallRequest
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="callRequest")
     */
    private $user;

    /**
     * @ORM\Column(type="integer")
     */
    private $userPhone;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\House", inversedBy="callRequest")
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
    public function getUserPhone()
    {
        return $this->userPhone;
    }

    /**
     * @param mixed $userPhone
     */
    public function setUserPhone($userPhone)
    {
        $this->userPhone = $userPhone;
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


}