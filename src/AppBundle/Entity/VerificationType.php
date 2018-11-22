<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/17/2018
 * Time: 11:26 AM
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="verification_type")
 */
class VerificationType
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
    private $verificationTypeText;

    /**
     * @ORM\Column(type="string")
     */
    private $verificationTypeDocument;

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
    public function getVerificationTypeText()
    {
        return $this->verificationTypeText;
    }

    /**
     * @param mixed $verificationTypeText
     */
    public function setVerificationTypeText($verificationTypeText)
    {
        $this->verificationTypeText = $verificationTypeText;
    }
    /**
     * @return mixed
     */
    public function getVerificationTypeDocument()
    {
        return $this->verificationTypeDocument;
    }

    /**
     * @param mixed $verificationTypeDocument
     */
    public function setVerificationTypeDocument($verificationTypeDocument)
    {
        $this->verificationTypeDocument = $verificationTypeDocument;
    }
}