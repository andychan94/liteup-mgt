<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10/27/2018
 * Time: 11:47 AM
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="notification")
 */
class Notification
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $notificationText;

    /**
     * @ORM\Column(type="integer")
     */
    private $agency;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isCheck = false;

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
    public function getNotificationText()
    {
        return $this->notificationText;
    }

    /**
     * @param mixed $notificationText
     */
    public function setNotificationText($notificationText)
    {
        $this->notificationText = $notificationText;
    }

    /**
     * @return mixed
     */
    public function getAgency()
    {
        return $this->agency;
    }

    /**
     * @param mixed $agency
     */
    public function setAgency($agency)
    {
        $this->agency = $agency;
    }

    /**
     * @return mixed
     */
    public function getisCheck()
    {
        return $this->isCheck;
    }

    /**
     * @param mixed $isCheck
     */
    public function setIsCheck($isCheck)
    {
        $this->isCheck = $isCheck;
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

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }


}