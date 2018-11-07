<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 9/1/2018
 * Time: 1:49 PM
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="verification_condition")
 */
class VerificationCondition
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\VerifyRequest", mappedBy="verifyCondition")
     */
    private $verifyRequest;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isTop;

    /**
     * @ORM\Column(type="string")
     */
    private $verificationConditionOrder;

    /**
     * @ORM\Column(type="string")
     */
    private $verificationConditionText;

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
    public function getisTop()
    {
        return $this->isTop;
    }

    /**
     * @param mixed $isTop
     */
    public function setIsTop($isTop)
    {
        $this->isTop = $isTop;
    }

    /**
     * @return mixed
     */
    public function getVerificationConditionOrder()
    {
        return $this->verificationConditionOrder;
    }

    /**
     * @param mixed $verificationConditionOrder
     */
    public function setVerificationConditionOrder($verificationConditionOrder)
    {
        $this->verificationConditionOrder = $verificationConditionOrder;
    }

    /**
     * @return mixed
     */
    public function getVerificationConditionText()
    {
        return $this->verificationConditionText;
    }

    /**
     * @param mixed $verificationConditionText
     */
    public function setVerificationConditionText($verificationConditionText)
    {
        $this->verificationConditionText = $verificationConditionText;
    }
}