<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10/22/2018
 * Time: 12:11 PM
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="email")
 */
class Email
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
    private $emailStatus;

    /**
     * @ORM\Column(type="string")
     */
    private $emailName;

    /**
     * @ORM\Column(type="string")
     */
    private $emailSubject;

    /**
     * @ORM\Column(type="text")
     */
    private $emailText;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $InSystemAlert;

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
    public function getEmailStatus()
    {
        return $this->emailStatus;
    }

    /**
     * @param mixed $emailStatus
     */
    public function setEmailStatus($emailStatus)
    {
        $this->emailStatus = $emailStatus;
    }

    /**
     * @return mixed
     */
    public function getEmailName()
    {
        return $this->emailName;
    }

    /**
     * @param mixed $emailName
     */
    public function setEmailName($emailName)
    {
        $this->emailName = $emailName;
    }

    /**
     * @return mixed
     */
    public function getEmailSubject()
    {
        return $this->emailSubject;
    }

    /**
     * @param mixed $emailSubject
     */
    public function setEmailSubject($emailSubject)
    {
        $this->emailSubject = $emailSubject;
    }

    /**
     * @return mixed
     */
    public function getEmailText()
    {
        return $this->emailText;
    }

    /**
     * @param mixed $emailText
     */
    public function setEmailText($emailText)
    {
        $this->emailText = $emailText;
    }

    /**
     * @return mixed
     */
    public function getInSystemAlert()
    {
        return $this->InSystemAlert;
    }

    /**
     * @param mixed $InSystemAlert
     */
    public function setInSystemAlert($InSystemAlert)
    {
        $this->InSystemAlert = $InSystemAlert;
    }
}