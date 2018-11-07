<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="contact_group")
 */
class ContactGroup
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
    private $contactGroupOrder;

    /**
     * @ORM\Column(type="string")
     */
    private $contactGroupTitle;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Contact", mappedBy="contactGroup")
     */
    private $contact;

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
    public function getContactGroupOrder()
    {
        return $this->contactGroupOrder;
    }

    /**
     * @param mixed $contactGroupOrder
     */
    public function setContactGroupOrder($contactGroupOrder)
    {
        $this->contactGroupOrder = $contactGroupOrder;
    }

    /**
     * @return mixed
     */
    public function getContactGroupTitle()
    {
        return $this->contactGroupTitle;
    }

    /**
     * @param mixed $contactGroupTitle
     */
    public function setContactGroupTitle($contactGroupTitle)
    {
        $this->contactGroupTitle = $contactGroupTitle;
    }

    /**
     * @return mixed
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @param mixed $contact
     */
    public function setContact($contact)
    {
        $this->contact = $contact;
    }

}