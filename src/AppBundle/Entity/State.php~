<?php

namespace AppBundle\Entity;

use AppBundle\Mapping\EntityBase;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * House
 *
 * @ORM\Table(name="state")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StateRepository")
 * @ORM\HasLifecycleCallbacks
 */
class State extends EntityBase
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * @ORM\OneToMany(targetEntity="Lga", mappedBy="state")
     */
    private $lgas;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setPosition(0);
        $this->lgas = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return State
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add lga
     *
     * @param \AppBundle\Entity\Lga $lga
     *
     * @return State
     */
    public function addLga(Lga $lga)
    {
        $this->lgas[] = $lga;

        return $this;
    }

    /**
     * Remove lga
     *
     * @param \AppBundle\Entity\Lga $lga
     */
    public function removeLga(Lga $lga)
    {
        $this->lgas->removeElement($lga);
    }

    /**
     * Get lgas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLgas()
    {
        return $this->lgas;
    }

    /**
     * Set position
     *
     * @param integer $position
     *
     * @return State
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }
}
