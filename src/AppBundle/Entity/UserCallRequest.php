<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/20/2018
 * Time: 10:54 AM
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_call_request")
 */
class UserCallRequest
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
    private $userName;

    /**
     * @ORM\Column(type="integer")
     */
    private $userTel;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\House", inversedBy="userCallRequest", fetch="EXTRA_LAZY")
     */
    private $property;

    /**
     * @ORM\Column(type="datetime")
     */
    private $requestedAt;

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
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @param mixed $userName
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    /**
     * @return mixed
     */
    public function getUserTel()
    {
        return $this->userTel;
    }

    /**
     * @param mixed $userTel
     */
    public function setUserTel($userTel)
    {
        $this->userTel = $userTel;
    }

    /**
     * @return mixed
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * @param mixed $property
     */
    public function setProperty($property)
    {
        $this->property = $property;
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

    public function __construct()
    {
        $this->requestedAt = new \DateTime();
    }


}