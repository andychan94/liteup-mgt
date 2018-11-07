<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 9/1/2018
 * Time: 3:16 PM
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="verify_request")
 */
class VerifyRequest
{
    const SERVER_PATH_TO_UPLOADS_FOLDER = "%kernel.root_dir%/../uploads/verify_docs";

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\VerificationCondition", inversedBy="verifyRequest")
     */
    private $verifyCondition;

    /**
     * @ORM\Column(type="string")
     */
    private $verifyDocs;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Agency",  inversedBy="verifyRequest", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $agency;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

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
    public function getVerifyCondition()
    {
        return $this->verifyCondition;
    }

    /**
     * @param mixed $verifyCondition
     */
    public function setVerifyCondition($verifyCondition)
    {
        $this->verifyCondition = $verifyCondition;
    }

    /**
     * @return mixed
     */
    public function getVerifyDocs()
    {
        return $this->verifyDocs;
    }

    /**
     * @param mixed $verifyDocs
     */
    public function setVerifyDocs($verifyDocs)
    {
        $this->verifyDocs = $verifyDocs;
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
    public function getisVerified()
    {
        return $this->isVerified;
    }

    /**
     * @param mixed $isVerified
     */
    public function setIsVerified($isVerified)
    {
        $this->isVerified = $isVerified;
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

    public function removeFile($id){

        if (file_exists(self::SERVER_PATH_TO_UPLOADS_FOLDER.'/'.$id)){

            unlink(self::SERVER_PATH_TO_UPLOADS_FOLDER.'/'.$id);
        }

    }


}