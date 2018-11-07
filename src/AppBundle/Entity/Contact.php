<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="contact")
 */
class Contact
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
    private $contactOrder;

    /**
     * @ORM\Column(type="string")
     */
    private $contactInfo;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ContactGroup", inversedBy="contact")
     */
    private $contactGroup;

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
    public function getContactOrder()
    {
        return $this->contactOrder;
    }

    /**
     * @param mixed $contactOrder
     */
    public function setContactOrder($contactOrder)
    {
        $this->contactOrder = $contactOrder;
    }

    /**
     * @return mixed
     */
    public function getContactInfo()
    {
        return $this->contactInfo;
    }

    /**
     * @param mixed $contactInfo
     */
    public function setContactInfo($contactInfo)
    {
        $this->contactInfo = $contactInfo;
    }

    /**
     * @return mixed
     */
    public function getContactGroup()
    {
        return $this->contactGroup;
    }

    /**
     * @param mixed $contactGroup
     */
    public function setContactGroup($contactGroup)
    {
        $this->contactGroup = $contactGroup;
    }

}